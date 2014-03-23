<?php

/* A convenient wrapper around the PDO statement class, including an iterator
interface. */
class SqlStatement implements Iterator {

	private $stmt;
	private $current;

	public function __construct($stmt) {
		$this->stmt = $stmt;
		$this->current = null;
	}

	/* Value binding. */

	/* Bind a result column to a variable. The column can be 1-indexed or
	referenced by name. */
	public function bind_output($col, &$var, $type = null) {
		if(is_null($type)) $result = $this->stmt->bindColumn($col, $var);
		else $result = $this->stmt->bindColumn($col, $var, self::type_value($column));
		if(!$result) $this->raise_error("unable to bind variable to column '$col'");
	}

	/* Bind an input parameter of a prepared statement to a variable. The
	parameter can be 1-indexed or referenced by name (include the colon).
	*/
	public function bind_input($param, &$var, $type = null) {
		if(is_null($type)) $result = $this->stmt->bindParam($param, $var);
		else $result = $this->stmt->bindParam($param, $var, self::type_value($column));
		if(!$result) $this->raise_error("unable to bind variable to prepared statement parameter '$param'");
	}

	/* Assign a value to an input parameter of a prepared statement. The
	parameter can be 1-indexed or referenced by name (include the colon).
	*/
	public function assign_input($param, $value, $type = null) {
		if(is_null($type)) $result = $this->stmt->bindValue($param, $value);
		else $result = $this->stmt->bindValue($param, $value, self::type_value($type));
		if(!$result) $this->raise_error("unable to assign value to prepared statement parameter '$param'");
	}

	/* Assign an array of values to the inputs of a prepared statement. */
	public function assign($values) {
		foreach($values as $k => $v) {
			$this->assign_input(is_int($k) ? $k + 1 : $k, $v);
		}
	}

	/* Execution and querying. */

	/* Execute this statement, optionally providing an array of values. */
	public function execute($values = null) {
		if(!(is_null($values) ? $this->stmt->execute() : $this->stmt->execute($values))) {
			$this->raise_error('unable to execute prepared statement');
		}
	}

	/* Equivalent to running $this->execute() and returning $this->value().
	 */
	public function evaluate($values = null) {
		$this->execute($values);
		return $this->value();
	}

	/* Ignore the rest of the records returned by this statement so as to
	make it executable again. */
	public function finish() {
		if(!$this->stmt->closeCursor()) $this->raise_error('unable to close cursor');
	}

	/* Return the first cell of the first row and ignore anything else. If
	no records are returned, return null. */
	public function value() {
		$result = $this->stmt->fetchColumn();
		if($result === false) return null;
		$this->finish();
		return $result;
	}

	/* Return the number of columns in the result set, or 0 if there is no
	result set. */
	public function num_columns() {
		return $this->stmt->columnCount();
	}

	/* Return the number of rows affected by the last execution of this
	statement. */
	public function affected_rows() {
		return $this->stmt->rowCount();
	}

	/* Return all of the rows in an array. This array is comparable with
	other arrays. */
	public function to_array() {
		$arrays = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
		$result = array();
		foreach($arrays as $a) $result[] = new Struct($a);
		return $result;
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
		/* TODO wrap in Struct object so invalid keys raise errors */
		$this->current = $this->stmt->fetch(PDO::FETCH_LAZY);
	}

	public function rewind() {
		if(is_null($this->current)) $this->next();
		else $this->current = $this->stmt->fetch(PDO::FETCH_LAZY, PDO::FETCH_ORI_ABS, 0);
	}

	public function valid() {
		return $this->current !== false;
	}

	/* Debugging. */

	/* Print debugging information to stdout. Return $this. */
	public function debug() {
		$this->stmt->debugDumpParams();
		return $this;
	}

	/* Private implementation details. */

	private static function type_value($name) {
		return constant('PDO::PARAM_' . strtoupper($name));
	}

	private function raise_error($msg) {
		list($state, $code, $errstr) = $this->stmt->errorInfo();
		throw new DatabaseError($msg, $errstr, $code, $state);
	}

}

?>
