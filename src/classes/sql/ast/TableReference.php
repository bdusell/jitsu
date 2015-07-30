<?php

namespace jitsu\sql\ast;

/* The name of a table with an optional database name.
 *
 * <table-reference> ->
 *   [<identifier> "."] <identifier>
 */
class TableReference extends Node {

	public $database;
	public $table;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_optional_class('Identifier', 'database');
		$this->validate_optional_class('Identifier', 'table');
	}

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

	public function cols(/* $name, ... */) {
		$ids = array();
		foreach(func_get_args() as $arg) {
			$ids[] = new Identifier(array('value' => $arg));
		}
		return new TableProjection(array(
			'table' => $this,
			'columns' => $ids
		));
	}

	public function col($name) {
		return new ColumnReference(array(
			'table' => $this,
			'column' => new Identifier(array(
				'value' => $name
			))
		));
	}
}

?>
