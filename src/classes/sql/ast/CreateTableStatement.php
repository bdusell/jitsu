<?php

namespace jitsu\sql\ast;

/* Create table statement.
 *
 * <create-table-stmt> ->
 *   "CREATE" ["TEMPORARY"] "TABLE" ["IF" "NOT" "EXISTS"]
 *   <table-ref>
 *   "(" <column-definition>+{","} ("," <table-constraint>)* ")"
 *   <table-modifier>*{","}
 */
class CreateTableStatement extends Statement {

	public $temporary;
	public $if_not_exists;
	public $name;
	public $columns;
	public $constraints;
	public $modifiers;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_bool('temporary');
		$this->validate_bool('if_not_exists');
		$this->validate_class('TableReference', 'name');
		$this->validate_array('ColumnDefinition', 'columns');
		$this->validate_emptyable_array('TableConstraint', 'constraints');
		$this->validate_emptyable_array('TableModifier', 'modifiers');
	}
}

?>
