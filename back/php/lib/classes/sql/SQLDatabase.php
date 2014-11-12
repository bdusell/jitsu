<?php

/* A set of convenient wrappers around the PDO MySQL library. */
abstract class SQLDatabase {

	public $driver   = null;
	public $host     = 'localhost';
	public $database = null;
	public $user     = null;
	public $password = null;
	public $charset  = null;

	private $conn = null;

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

	/* Connect to the database upon construction. */
	public function __construct() {
		try {
			$driver = $this->driver;
			$str = null;
			if($driver === 'mysql') {
				$settings = array(
					'host=' . $this->host,
					'dbname=' . $this->database
				);
				if(!is_null($charset = $this->charset)) {
					$settings[] = "charset=$charset";
				}
				$str = $driver . ':' . join(';', $settings);
			}
			elseif($driver === 'sqlite' || $driver === 'sqlite2') {
				$str = $driver . ':' . $this->database;
			}
			$this->conn = new PDO($str, $this->user, $this->password);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			if($driver === 'mysql' && !is_null($charset)) {
				$this->conn->exec('set names ' . $this->charset);
			}
			if($driver === 'sqlite' || $driver === 'sqlite2') {
				$this->conn->exec('pragma foreign_keys = on');
			}
		}
		catch(PDOException $e) {
			self::exception_error('database connection failed', $e);
		}
	}

	/* Querying. */

	/* Execute a one-shot SQL query and return the resulting rows in an
	iterable SQLStatement object. The remaining parameters may be used to
	pass arguments to the query. If there is only a single array passed as
	an additional argument, its contents are used as the parameters.

	Synopsis:
		$stmt = $db->query($sql_code);
		$stmt = $db->query($sql_code, $arg1, $arg2, ...);
		$stmt = $db->query($sql_code, $arg_array);
		foreach($stmt as $row) { $row->column ... }

	*/
	public function query(/* $query, [ $arg_array | $arg1, $arg2, ... ] */) {
		$args = func_get_args();
		$query = array_shift($args);
		if(!$args) {
			if(($result = $this->conn->query($query)) === false) {
				$this->result_error('unable to execute SQL query', $query);
			}
			return self::wrap_statement($result);
		}
		else {
			$stmt = $this->prepare($query);
			if(count($args) === 1 && is_array($args[0])) {
				$args = $args[0];
			}
			$stmt->execute($args);
			return $stmt;
		}
	}

	/* Return the first row of a query and ignore the rest. */
	public function row(/* $query, ... */) {
		$args = func_get_args();
		return call_user_func_array(array($this, 'query'), $args)->first();
	}

	/* Return the first column of the first row and ignore everything
	 * else. */
	public function evaluate(/* $query, ... */) {
		$args = func_get_args();
		return call_user_func_array(array($this, 'query'), $args)->value();
	}

	/* Execute a SQL statement. If there are no arguments after the SQL
	statement, return the number of affected rows. Otherwise, return
	a SQLStatement. */
	public function execute(/* $statement, [ $arg_array | $arg1, $arg2, ... ] */) {
		$args = func_get_args();
		$statement = array_shift($args);
		if(!$args) {
			if(($result = $this->conn->exec($statement)) === false) {
				$this->result_error('unable to execute SQL statement', $statement);
			}
			return $result;
		}
		else {
			$args = func_get_args();
			return call_user_func_array(array($this, 'query'), $args);
		}
	}

	/* Create a prepared SQL statement. */
	public function prepare($statement) {
		try {
			if(($result = $this->conn->prepare($statement)) === false) {
				$this->result_error('unable to prepare statement', $statement);
			}
		}
		catch(PDOException $e) {
			self::exception_error('unable to prepare statement', $e, $statement);
		}
		return self::wrap_statement($result);
	}

	/* Escape and quote a string value for interpolation in a SQL query. */
	public function quote($s) {
		if(($result = $this->conn->quote()) === false) {
			$this->result_error("unable to quote string value '$s'");
		}
		return $result;
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
		}
		catch(PDOException $e) {
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

	/* Get a database connection attribute. The name passed should be a
	string (case-insensitive) and corresponds to a PDO constant with the
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

	// Wrap a statement
	private static function wrap_statement($stmt) {
		return new SQLStatement($stmt);
	}
}

?>
