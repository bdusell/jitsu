<?php

namespace phrame\sql\ast;

/* A reference to a column in a FROM clause.
 *
 * <column-reference> ->
 *   [<table-reference> "."] <identifier>
 */
class ColumnReference extends AtomicExpression {

	public $table;
	public $column;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_optional_class('TableReference', 'table');
		$this->validate_class('Identifier', 'column');
	}
}

?>
