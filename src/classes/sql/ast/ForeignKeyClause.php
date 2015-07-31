<?php

namespace jitsu\sql\ast;

/* A `REFERENCES` clause.
 *
 * <foreign-key-clause> ->
 *   "REFERENCES" <table-ref>
 *   ["(" <identifier>+{","} ")"]
 *   ["ON" "DELETE" <foreign-key-trigger-name>]
 *   ["ON" "UPDATE" <foreign-key-trigger-name>]
 *   [<deferrable-clause>]
 */
class ForeignKeyClause extends Node {

	const SET_NULL  = 'SET NULL';
	const CASCADE   = 'CASCADE';
	const RESTRICT  = 'RESTRICT';
	const NO_ACTION = 'NO ACTION';

	const DEFERRED  = 'DEFERRED';
	const IMMEDIATE = 'IMMEDIATE';

	public $table;
	public $columns;
	public $on_delete;
	public $on_update;
	public $deferrable;
	public $initially;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('TableReference', 'table');
		$this->validate_optional_array('Identifier', 'columns');
		$this->validate_optional_const('on_delete');
		$this->validate_optional_const('on_update');
		$this->validate_optional_bool('deferrable');
		$this->validate_optional_const('initially');
	}
}

?>
