<?php

namespace phrame\sql\ast;

class ColumnReference extends Expression {

	public $table;
	public $column;

	public function __construct($table, $column) {
		$this->table = $table;
		$this->column = $column;
	}
}

?>
