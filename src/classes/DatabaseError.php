<?php

/* An exception extension for database-related errors. */
class DatabaseError extends Exception {

	private $errstr;
	private $state;
	private $sql;

	/* Create a database exception object with a user-supplied message, as
	well as the error string returned by the database driver or library
	after the failed operation. Optionally give the error code and SQL
	state reported after the error, as well as any SQL code which might be
	responsible. */
	public function __construct($msg, $errstr, $code = null, $state = null, $sql = null) {
		parent::__construct($msg, $code);
		$this->errstr = $errstr;
		$this->state = $state;
		$this->sql = $sql;
	}

	/* Get the SQL state abbreviation, as a string, reported by the
	database driver, or null if none was reported. */
	public function getSqlState() {
		return $this->state;
	}

	/* Get the error string reported by the database driver, or null if
	none was reported. */
	public function getErrorString() {
		return $this->errstr;
	}

	/* Get the SQL code which caused the error, or null if no SQL code
	caused the error. */
	public function getSql() {
		return $this->sql;
	}

	/* Return a suitable string representation of the database error. */
	public function __toString() {
		$result = parent::__toString();
		if(!is_null($this->errstr)) $result .= "\nerror string: " . $this->errstr;
		if(!is_null($this->state)) $result .= "\nSQL state: " . $this->state;
		if(!is_null($this->sql)) $result .= "\nSQL code: " . $this->sql;
		return $result;
	}

}

?>
