<?php

namespace phrame\sql\ast;

class TableReference extends Node {

	public $database;
	public $table;

	public function as_self() {
		return new TableExpression(array(
			'table' => $this
		));
	}

	public function as_name($name) {
		return new TableExpression(array(
			'table' => $this,
			'as' => $name
		));
	}
}

?>
