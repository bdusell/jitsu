<?php

namespace jitsu\sql\ast;

/* A column definition.
 *
 * <column-definition> ->
 *   <identifier> <type>
 *   [<collate-clause>]
 *   [<not-null-clause>]
 *   [<default-value-clause>]
 *   [<autoincrement-clause>]
 *   [<key-clause>]
 *   [<foreign-key-clause>]
 */
class ColumnDefinition extends Node {

	public $name;
	public $type;
	public $not_null;
	public $default;
	public $autoincrement;
	public $key;
	public $foreign_key;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Identifier', 'name');
		$this->validate_class('Type', 'type');
		$this->validate_optional_class('NotNullClause', 'not_null');
		$this->validate_optional_class('DefaultValueClause', 'default');
		$this->validate_optional_class('AutoincrementClause', 'autoincrement');
		$this->validate_optional_class('KeyClause', 'key');
		$this->validate_optional_class('ForeignKeyClause', 'foreign_key');
	}

	public function not_null() {
		$this->not_null = new NotNullClause(array());
		return $this;
	}

	public function default_value($expr) {
		$this->default = new DefaultValueClause(array(
			'expr' => $expr
		));
		return $this;
	}

	public function autoincrement() {
		$this->autoincrement = new AutoincrementClause(array());
		return $this;
	}

	public function primary_key() {
		$this->key = new PrimaryKeyClause(array());
		return $this;
	}

	public function is_primary_key() {
		return $this->key && $this->key->is_primary_key();
	}

	public function unique() {
		$this->key = new UniqueClause(array());
		return $this;
	}

	public function foreign_key($fk_clause) {
		$this->key = $fk_clause;
		return $this;
	}
}

?>
