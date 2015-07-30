<?php

namespace jitsu\sql\ast;

/* An expression consisting of a unary operator (left or right) and a sub-
 * expression.
 *
 * <unary-operator-expression> ->
 *   <negation-expression> |
 *   <not-expression>
 */
class UnaryOperatorExpression extends Expression {

	public $expr;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
	}
}

?>
