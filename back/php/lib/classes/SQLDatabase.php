<?php

/* A useful wrapper around the PDO library. Currently only tested against the
 * mysql and sqlite drivers. */
abstract class SQLDatabase {

	private $conn = null;

	/* Driver constants. */
	const mysql = 'mysql';
	const sqlite = 'sqlite';
	const sqlite2 = 'sqlite2';

	/* Protected members for configuring the database. Set these in the
	 * constructor of a class which inherits from `SQLDatabase`,
	 * before calling `parent::__construct()`. */

	/* Database driver. Use one of the driver constants. */
	protected $driver = null;

	/* MySQL host. Default is `localhost`. */
	protected $host = 'localhost';

	/* Database name. For MySQL this is the name of the database. For
	 * SQLite this is the path of the database file. */
	protected $database = null;

	/* MySQL username. */
	protected $user = null;

	/* MySQL password. */
	protected $password = null;

	/* MySQL character set. The default, `utf8mb4`, supports all Unicode
	 * characters. */
	protected $charset = 'utf8mb4';

	/* Fetch mode. Determines what kind of object rows are fetched as. Use
	 * the `PDO::FETCH_*` constants directly. The default,
	 * `PDO::FETCH_OBJ`, causes rows to be returned as objects with
	 * property names corresponding to column names. */
	protected $mode = PDO::FETCH_OBJ;

	/* Connect to the database upon construction. */
	public function __construct() {
		try {
			/* Create the appropriate driver string. */
			$driver = $this->driver;
			$driver_str = null;
			$is_sqlite = false;
			if($driver === self::mysql) {
				$settings = array(
					'host=' . $this->host,
					'dbname=' . $this->database
				);
				if($this->charset !== null) {
					$settings[] = 'charset=' . $this->charset;
				}
				$driver_str = 'mysql:' . join(';', $settings);
			} elseif($driver === self::sqlite || $driver === self::sqlite2) {
				$is_sqlite = true;
				$driver_str = $driver . ':' . $this->database;
			}

			/* Instantiate the PDO connection. */
			$this->conn = new PDO($driver_str, $this->user, $this->password);

			/* Raise exceptions on errors. */
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			/* Execute certain commands upon startup depending on the driver. */
			if($driver === self::mysql && $charset !== null) {
				$this->conn->exec('set names ' . $this->charset);
			} elseif($is_sqlite) {
				$this->conn->exec('pragma foreign_keys = on');
			}
		} catch(PDOException $e) {
			self::exception_error('database connection failed', $e);
		}
	}

	/* Querying. */

	/* Execute a one-shot SQL query and return the resulting rows in an
	iterable `SQLStatement` object. The remaining parameters may be used to
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

	/* Execute a SQL statement. If there are no arguments after the SQL
	statement, return the number of affected rows. Otherwise, return
	a `SQLStatement`. */
	public function execute(/* $statement, ... */) {
		self::args(func_get_args(), $statement, $args);
		return $this->execute_with($statement, $args);
	}

	public function execute_with($statement, $args) {
		if($args) {
			return $this->query_with($statement, $args);
		} else {
			if(($result = $this->conn->exec($statement)) === false) {
				$this->result_error('unable to execute SQL statement', $statement);
			}
			return $result;
		}
	}

	/* Prepare a SQL statement and return it as a `SQLStatement`. */
	public function prepare($statement) {
		try {
			if(($result = $this->conn->prepare($statement)) === false) {
				$this->result_error('unable to prepare statement', $statement);
			}
		} catch(PDOException $e) {
			self::exception_error('unable to prepare statement', $e, $statement);
		}
		return $this->wrap_statement($result);
	}

	/* Escape and quote a string value for interpolation in a SQL query.
	 * Note that the result includes quotes added around the string. */
	public function quote($s) {
		if(($result = $this->conn->quote()) === false) {
			$this->result_error("unable to quote string value '$s'");
		}
		return $result;
	}

	/* Escape characters in a string that have special meaning in SQL
	 * "like" patterns. Note that this should be coupled with an `ESCAPE`
	 * clause in the SQL; for example,
	 *     "percentage" LIKE "%0\%" ESCAPE '\'
	 * A `\` is the default escape character. */
	public function escape_like($s, $esc = '\\') {
		return str_replace(
			array('%', '_'),
			array($esc . '%', $esc . '_'),
			$text
		);
	}

	/* Get the id of the last inserted record. */
	public function last_insert_id() {
		$result = $this->conn->lastInsertId();
		if($this->conn->errorCode() === 'IM001') {
			$this->result_error('unable to get last insert id');
		}
		return $result;
	}

	/* Transaction handling. */

	/* Begin a transaction. */
	public function begin() {
		if(!$this->conn->beginTransaction()) {
			$this->result_error('unable to begin transaction');
		}
	}

	/* Tell whether a transaction is active. */
	public function in_transaction() {
		return $this->conn->inTransaction();
	}

	/* Roll back a transaction. */
	public function rollback() {
		try {
			if(!$this->conn->rollBack()) {
				$this->result_error('unable to roll back transaction because no transaction is active');
			}
		} catch(PDOException $e) {
			self::exception_error('unable to roll back transaction', $e);
		}
	}

	/* Commit a transaction. */
	public function commit() {
		if(!$this->conn->commit()) {
			$this->result_error('unable to commit transaction');
		}
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
	PDO::ATTR_ prefix dropped. */
	public function attribute($name) {
		if(is_null($result = $this->conn->getAttribute(self::attr_value($name)))) {
			$this->result_error("unable to get attribute '$name'");
		}
		return $result;
	}

	/* Set a database connection attribute, using the same attribute name
	convention as attribute(). The value should be a string (case-
	insensitive) corresponding to a PDO constant with the PDO:: prefix
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
		return PDO::getAvailableDrivers();
	}

	/* Private implementation details. */

	// Parse the argument list to a method.
	private static function args($args, &$query, &$sql_args) {
		$query = array_shift($args);
		if(count($args) == 1 && is_array($args[0])) {
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
		throw new SQLError("$msg: $errstr", $errstr, $code, $state, $sql);
	}

	// Convert an attribute name to its integer constant.
	private static function attr_value($name) {
		return constant('PDO::ATTR_' . strtoupper($name));
	}

	// Wrap a statement.
	private function wrap_statement($stmt) {
		return new SQLStatement($stmt, $this->mode);
	}
}

?>
