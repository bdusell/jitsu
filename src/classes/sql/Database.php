<?php

namespace jitsu\sql;

/* A useful wrapper around the PDO library. */
abstract class Database {

	private $conn = null;

	private $mode = \PDO::FETCH_OBJ;

	/* Connect to the database upon construction. Accepts a PDO driver
	 * string and an optional username and password. An array of PDO
	 * options may also be passed. */
	public function __construct(
		$driver_str,
		$username = null,
		$password = null,
		$options = null
	) {
		if($options === null) $options = array();
		try {
			$this->conn = new \PDO(
				$driver_str,
				$username,
				$password,
				array(
					/* Check error codes and throw our own
					* `Error` exceptions. */
					\PDO::ATTR_ERRMODE => \PDO::ERRMODE_SILENT
				) + $options
			);
		} catch(\PDOException $e) {
			/* The PDO constructor always throws an exception on
			 * error. */
			self::exception_error('database connection failed', $e);
		}
	}

	/* Querying. */

	/* Execute a one-shot SQL query and return the resulting rows in an
	iterable `Statement` object. The remaining parameters may be used to
	pass arguments to the query. If there is only a single array passed as
	an additional argument, its contents are used as the parameters.

	Synopsis:
		$stmt = $db->query($sql_code);
		$stmt = $db->query($sql_code, $arg1, $arg2, ...);
		$stmt = $db->query($sql_code, $arg_array);
		foreach($stmt as $row) { $row->column_name ... }

	*/
	public function query(/* $query, [ $args | $arg1, $arg2, ... ] */) {
		self::args(func_get_args(), $query, $args);
		return $this->query_with($query, $args);
	}

	/* Same as `query`, but arguments are always passed in a single `$args`
	 * array. */
	public function query_with($query, $args) {
		if($args) {
			/* If there are arguments, prepare the statement and
			 * execute it. */
			$stmt = $this->prepare($query);
			$stmt->assign_with($args);
			$stmt->execute();
			return $stmt;
		} else {
			/* Otherwise, use the one-shot query method, without
			 * using a prepared statement. */
			if(($result = $this->conn->query($query)) === false) {
				$this->result_error('unable to execute SQL query', $query);
			}
			return $this->wrap_statement($result);
		}
	}

	/* Return the first row of a query and ignore the rest. */
	public function row(/* $query, ... */) {
		self::args(func_get_args(), $query, $args);
		return $this->row_with($query, $args);
	}

	public function row_with($query, $args) {
		return self::query_with($query, $args)->first();
	}

	/* Return the first column of the first row and ignore everything
	 * else. */
	public function evaluate(/* $query, ... */) {
		self::args(func_get_args(), $query, $args);
		return $this->evaluate_with($query, $args);
	}

	public function evaluate_with($query, $args) {
		return $this->query_with($query, $args)->value();
	}

	/* Execute a SQL statement. If called with arguments, returns a
	 * `Statement`. Note that the number of affected rows is available via
	 * `Statement->affected_rows()`. If called with no arguments, returns a
	 * `StatementStub` object instead, which provides only the
	 * `affected_rows()` method. */
	public function execute(/* $statement, ... */) {
		self::args(func_get_args(), $statement, $args);
		return $this->execute_with($statement, $args);
	}

	public function execute_with($statement, $args) {
		if($args) {
			/* If there are arguments, prepare the statement and
			 * execute it. */
			return $this->query_with($statement, $args);
		} else {
			/* Otherwise, use the one-shot exec method, without
			 * using a prepared statement. */
			if(($result = $this->conn->exec($statement)) === false) {
				$this->result_error('unable to execute SQL statement', $statement);
			}
			return new StatementStub($result);
		}
	}

	/* Prepare a SQL statement and return it as a `Statement`. */
	public function prepare($statement) {
		if(($result = $this->conn->prepare($statement)) === false) {
			$this->result_error('unable to prepare statement', $statement);
		}
		return $this->wrap_statement($result);
	}

	/* Escape and quote a string value for interpolation in a SQL query.
	 * Note that the result *includes* quotes added around the string. */
	public function quote($s) {
		if(($result = $this->conn->quote($s)) === false) {
			$this->result_error('driver does not implement string quoting');
		}
		return $result;
	}

	/* Escape characters in a string that have special meaning in SQL
	 * "like" patterns. Note that this should be coupled with an `ESCAPE`
	 * clause in the SQL; for example,
	 *     "column" LIKE '%foo\%bar%' ESCAPE '\'
	 * A `\` is the default escape character. */
	public function escape_like($s, $esc = '\\') {
		return str_replace(
			array('%', '_'),
			array($esc . '%', $esc . '_'),
			$text
		);
	}

	/* Get the id of the last inserted record. *Note that the result is
	 * always a string*. */
	public function last_insert_id() {
		$result = $this->conn->lastInsertId();
		if($this->conn->errorCode() === 'IM001') {
			$this->result_error('driver does not support getting last insert ID');
		}
		return $result;
	}

	/* Transaction handling. */

	/* Begin a transaction. Note that uncommitted transactions are
	 * automatically rolled back when the script terminates. */
	public function begin() {
		try {
			$r = $this->conn->beginTransaction();
		} catch(\PDOException $e) {
			$this->exception_error('database does not support transactions');
		}
		if(!$r) {
			$this->result_error('unable to begin transaction');
		}
	}

	/* Return whether a transaction is active. */
	public function in_transaction() {
		return $this->conn->inTransaction();
	}

	/* Roll back the current transaction. */
	public function rollback() {
		try {
			$r = $this->conn->rollBack();
		} catch(\PDOException $e) {
			self::exception_error('unable to roll back transaction because no transaction is active', $e);
		}
		if(!$r) {
			$this->result_error('unable to roll back transaction');
		}
	}

	/* Commit the current transaction. */
	public function commit() {
		if(!$this->conn->commit()) {
			$this->result_error('unable to commit transaction');
		}
	}

	/* Run a callback safely in a transaction. If the callback throws an
	 * exception, the transaction will be rolled back. */
	public function transaction($callback) {
		$this->begin();
		try {
			call_user_func($callback);
		} catch(\Exception $e) {
			$this->rollback();
			throw $e;
		}
		$this->commit();
	}

	/* Database connection attributes. */

	private static $attrs = array(
		'autocommit',
		'case',
		'client_version',
		'connection_status',
		'driver_name',
		'errmode',
		'oracle_nulls',
		'persistent',
		'prefetch',
		'server_info',
		'server_version',
		'timeout'
	);

	/* Get a database connection attribute. The name passed should be a
	string (case-insensitive) and correspond to a PDO constant with the
	`PDO::ATTR_` prefix dropped. */
	public function attribute($name) {
		if(($result = $this->conn->getAttribute(self::attr_value($name))) === null) {
			$this->result_error("unable to get attribute '$name'");
		}
		return $result;
	}

	/* Set a database connection attribute, using the same attribute name
	convention as `attribute()`. The value should be a string (case-
	insensitive) corresponding to a PDO constant with the `PDO::` prefix
	dropped. */
	public function set_attribute($name, $value) {
		if(!$this->conn->setAttribute(self::attr_value($name), constant('PDO::' . strtoupper($value)))) {
			$this->result_error("unable to set attribute '$name' to '$value'");
		}
	}

	/* Generate a mapping of all attribute names and values. */
	public function attributes() {
		$result = array();
		foreach(self::$attrs as $attr) {
			$result[$attr] = $this->attribute($attr);
		}
		return $result;
	}

	/* Database driver handling. */

	/* Get a list of the available database drivers. */
	public static function drivers() {
		return \PDO::getAvailableDrivers();
	}

	/* Miscellaneous. */

	/* Get the underlying PDO connection object. */
	public function connection() {
		return $this->conn;
	}

	/* Access the fetch mode. Give no argument to get the current mode and
	 * provide an argument to set it. The fetch mode determines the form in
	 * which rows are fetched. Use the `PDO::FETCH_` constants directly.
	 * The default, `PDO::FETCH_OBJ`, causes rows to be returned as
	 * `stdClass` objects with property names corresponding to column
	 * names. */
	public function fetch_mode($mode = null) {
		if(func_num_args() === 0) return $mode;
		else $this->mode = $mode;
	}

	/* Private implementation details. */

	// Parse the argument list to a method.
	private static function args($args, &$query, &$sql_args) {
		$query = array_shift($args);
		if(count($args) === 1 && is_array($args[0])) {
			$sql_args = $args[0];
		} else {
			$sql_args = $args;
		}
	}

	// Raise an error based on a false return value.
	private function result_error($msg, $sql = null) {
		list($state, $code, $errstr) = $this->conn->errorInfo();
		self::raise_error($msg, $errstr, $code, $state, $sql);
	}

	// Raise an error based on a caught exception.
	private static function exception_error($msg, $e, $sql = null) {
		self::raise_error($msg, $e->getMessage(), $e->getCode(), null, $sql);
	}

	// Raise an error.
	private static function raise_error($msg, $errstr, $code = null, $state = null, $sql = null) {
		throw new \jitsu\sql\Error("$msg: $errstr", $errstr, $code, $state, $sql);
	}

	// Convert an attribute name to its integer constant.
	private static function attr_value($name) {
		return constant('PDO::ATTR_' . strtoupper($name));
	}

	// Wrap a statement.
	private function wrap_statement($stmt) {
		return new \jitsu\sql\Statement($stmt, $this->mode);
	}
}

?>
