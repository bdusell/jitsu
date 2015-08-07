<?php

namespace jitsu\sql\ast;

/* A table name followed by a list of column names.
 *
 * <table-projection> ->
 *   <table-reference> "(" <identifier>+{","} ")"
 */
class TableProjection extends Node {

	public $table;
	public $columns;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('TableReference', 'table');
		$this->validate_array('Identifier', 'columns');
	}
}

?>
