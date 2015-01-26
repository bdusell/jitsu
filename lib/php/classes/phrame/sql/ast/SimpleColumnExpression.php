<?php

namespace phrame\sql\ast;

/* An expression with an optional AS clause.
 *
 * <simple-column-expression> ->
 *   <expression> ["AS" <identifier>]
 */
class SimpleColumnExpression extends ColumnExpression {

	public $expr;
	public $as;

	public function __construct($expr, $as = null) {
		parent::__construct(array('expr' => $expr, 'as' => $as));
		$this->validate_class('Expression', 'expr');
		$this->validate_optional_class('Identifier', 'as');
	}
}

?>
