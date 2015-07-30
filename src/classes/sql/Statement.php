<?php

namespace jitsu\sql;

/* A convenient wrapper around the PDO statement class which implements an
 * iterator interface. */
class Statement implements \Iterator {

	private $stmt;
	private $current;

	/* Construct with a `PDOStatement` instance. Optionally specify a fetch
	 * mode, which determines what kind of object rows are returned as.
	 * Use the `PDO::FETCH_` constants directly. The default is
	 * `PDO::FETCH_OBJ`, so that rows are returned as objects with property
	 * names corresponding to column names. */
	public function __construct($stmt, $mode = \PDO::FETCH_OBJ) {
		$this->stmt = $stmt;
		$this->current = null;
		$this->mode = $mode;
	}

	/* Value binding. */

	/* Bind a result column to a variable. The column can be 1-indexed or
	 * referenced by name.
	 *
	 * Example:
	 *
	 *     $stmt = $db->prepare('select id, name from users');
	 *     $stmt->bind_output('name', $name);
	 *     foreach($stmt as $row) echo $name, "\n";
	 *
	 * A type may optionally be specified. The following values may be
	 * passed as strings: `bool`, `null`, `int`, `str`, `lob` (large
	 * object). If a type is not passed, then it is inferred from the type
	 * of the PHP value passed.
	 *
	 * The `$inout` parameter specifies whether the column is an
	 * `INOUT` parameter for a stored procedure.
	 */
	public function bind_output($col, &$var, $type = null, $inout = false) {
		if($type === null) {
			$result = $this->stmt->bindColumn($col, $var);
		} else {
			$result = $this->stmt->bindColumn($col, $var, self::type_value($type, $inout));
		}
		if(!$result) {
			$this->raise_error("unable to bind variable to column '$col'");
		}
	}

	/* Bind an input parameter of a prepared statement to a variable. The
	 * parameter can be 1-indexed or referenced by name (include the
	 * colon).
	 *
	 * Example 1:
	 *
	 *     $stmt = $db->prepare('select id, name from users where phone = ?');
	 *     $stmt->bind_input(1, $phone);
	 *     $phone = '5551234567';
	 *     $stmt->execute();
	 *
	 * Example 2:
	 *
	 *     $stmt = $db->prepare('select id, name from users where phone = :phone');
	 *     $stmt->bind_input(':phone', $phone);
	 */
	public function bind_input($param, &$var, $type = null, $inout = false) {
		if($type === null) {
			$result = $this->stmt->bindParam($param, $var);
		} else {
			$result = $this->stmt->bindParam($param, $var, self::type_value($type, $inout));
		}
		if(!$result) {
			$this->raise_error("unable to bind variable to prepared statement parameter '$param'");
		}
	}

	/* Assign a value to an input parameter of a prepared statement. If
	 * `$type` is null, the appropriate type is used based on the PHP type
	 * of the value. */
	public function assign_input($param, $value, $type = null, $inout = false) {
		if($type === null) {
			$result = $this->stmt->bindValue($param, $value, self::intuit_type($value));
		} else {
			$result = $this->stmt->bindValue($param, $value, self::type_value($type, $inout));
		}
		if(!$result) {
			$this->raise_error("unable to assign value to prepared statement parameter '$param'");
		}
	}

	/* Assign an array of values to the inputs of a prepared statement. For
	 * named parameters, pass an array mapping names (no colons) to values.
	 * For positional parameters, pass a sequential array (0-indexed). A
	 * positional parameter array may be passed as a single array argument
	 * or as a variadic argument list.
	 *
	 * Example:
	 *
	 *     $stmt = $db->prepare('select * from users where first = ? and last = ?');
	 *     $stmt->assign('John', 'Doe');
	 */
	public function assign(/* $values | $value1, $value2, ... */) {
		return $this->assign_with(
			func_num_args() > 1 || !is_array(func_get_arg(0)) ?
			func_get_args() :
			func_get_arg(0)
		);
	}

	public function assign_with($values) {
		if($values) {
			if(array_key_exists(0, $values)) {
				foreach($values as $i => $value) {
					$this->assign_input($i + 1, $value);
				}
			} else {
				foreach($values as $name => $value) {
					$this->assign_input(':' . $name, $value);
				}
			}
		}
	}

	/* Execution and querying. */

	/* Execute this statement, optionally providing a 0-indexed array of
	 * values. The values array may be convenient in simple cases, but it
	 * only works for positional parameters and casts all values to the
	 * SQL string type (`'str'`). The string casting may cause values to
	 * be inexplicably stored as integers but later retrieved as strings in
	 * SQLite, which could cause some annoyance. A more flexible
	 * alternative is to call `assign` beforehand. */
	public function execute($values = null) {
		if(!($values === null ? $this->stmt->execute() : $this->stmt->execute($values))) {
			$this->raise_error('unable to execute prepared statement');
		}
	}

	/* Equivalent to running `$this->execute()` and returning
	 * `$this->value()`. */
	public function evaluate($values = null) {
		$this->execute($values);
		return $this->value();
	}

	/* Ignore the rest of the records returned by this statement so as to
	 * make it executable again. */
	public function finish() {
		if(!$this->stmt->closeCursor()) {
			$this->raise_error('unable to close cursor');
		}
	}

	/* Return the first row and ignore the rest. If there are no records
	 * returned, return null. */
	public function first() {
		foreach($this as $row) {
			// Note that this doesn't work with some fetch modes
			$this->finish();
			return $row;
		}
		return null;
	}

	/* Return the first cell of the first row and ignore anything else. If
	 * no records are returned, return null. */
	public function value() {
		$result = $this->stmt->fetchColumn();
		if($result === false) return null;
		$this->finish();
		return $result;
	}

	/* Return the number of columns in the result set, or 0 if there is no
	 * result set. */
	public function column_count() {
		return $this->stmt->columnCount();
	}

	/* Return the number of rows affected by the last execution of this
	 * statement. */
	public function affected_rows() {
		return $this->stmt->rowCount();
	}

	/* Return all of the rows copied into an array. */
	public function to_array() {
		return $this->stmt->fetchAll($this->mode);
	}

	/* Return all of the rows passed through a function and copied into an
	 * array. The columns of each row are passed as positional parameters
	 * to the function. */
	public function map($callback) {
		return $this->stmt->fetchAll(\PDO::FETCH_FUNC, $callback);
	}

	/* Advance to the next set of rows returned by the query, which is
	supported by some stored procedures. */
	public function next_rowset() {
		if(!$this->stmt->nextRowset()) {
			$this->raise_error('unable to advance to next rowset');
		}
	}

	/* Iterator interface. */

	public function current() {
		return $this->current;
	}

	public function key() {
		return null;
	}

	public function next() {
		$this->current = $this->stmt->fetch($this->mode);
	}

	public function rewind() {
		// Called before the first iteration
		// Pre-load the first row
		if($this->current === null) {
			$this->next();
		} else {
			$this->current = $this->stmt->fetch($this->mode, \PDO::FETCH_ORI_ABS, 0);
		}
	}

	public function valid() {
		return $this->current !== false;
	}

	/* Debugging. */

	/* Print debugging information to stdout. Returns `$this`. */
	public function debug() {
		$this->stmt->debugDumpParams();
		return $this;
	}

	/* Private implementation details. */

	private static function intuit_type($value) {
		if(is_string($value)) {
			return \PDO::PARAM_STR;
		} elseif(is_int($value)) {
			return \PDO::PARAM_INT;
		} elseif(is_bool($value)) {
			return \PDO::PARAM_BOOL;
		} elseif(is_null($value)) {
			return \PDO::PARAM_NULL;
		} else {
			return \PDO::PARAM_STR;
		}
	}

	private static function type_value($name, $inout) {
		return constant('PDO::PARAM_' . strtoupper($name)) | ($inout ? \PDO::PARAM_INPUT_OUTPUT : 0);
	}

	private function raise_error($msg) {
		list($state, $code, $errstr) = $this->stmt->errorInfo();
		throw new \jitsu\sql\Error($msg, $errstr, $code, $state);
	}
}

?>
