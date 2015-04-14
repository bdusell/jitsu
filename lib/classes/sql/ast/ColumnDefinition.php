<?php

namespace phrame\sql\ast;

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
}

?>
