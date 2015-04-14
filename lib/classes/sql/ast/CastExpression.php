<?php

namespace phrame\sql\ast;

/* A CAST expression to cast an expression to a certain type.
 *
 * <cast-expression> ->
 *   "CAST" "(" <expression> "AS" <type> ")"
 */
class CastExpression extends AtomicExpression {

	public $expr;
	public $type;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
		$this->validate_class('Type', 'type');
	}
}

?>
