<?php

namespace phrame\sql\ast;

class TableReference extends FromExpression {

	public $database;
	public $table;
	public $as;

	public function __construct($database, $table, $as) {
		$this->database = $database;
		$this->table = $table;
		$this->as = $as;
	}
}

?>
