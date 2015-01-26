<?php

namespace phrame\sql\ast;

/* A table name followed by a list of column names.
 *
 * <table-projection> ->
 *   <table-reference> -> "(" <identifier>+{","} ")"
 */
class TableProjection extends Node {

	public $table;
	public $columns;

	public function __construct($table, $columns) {
		parent::__construct(array('table' => $table, 'columns' => $columns));
		$this->validate_class('TableReference', 'table');
		$this->validate_array('Identifier', 'columns');
	}
}

?>
