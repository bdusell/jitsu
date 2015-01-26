<?php

namespace phrame\sql\ast;

/* A star (*) in a SELECT clause with an optional table name.
 *
 * <wildcard-column-expression> ->
 *   [<identifier> "."] "*"
 */
class WildcardColumnExpression extends ColumnExpression {

	public $table;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_optional_class('Identifier', 'table');
	}
}

?>
