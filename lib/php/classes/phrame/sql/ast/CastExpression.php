<?php

namespace phrame\sql\ast;

/* A CAST expression to cast an expression to a certain type.
 *
 * <cast-expression> ->
 *   "CAST" "(" <expression> "AS" <type-name> ")"
 */
class CastExpression extends AtomicExpression {

	public $expr;
	public $type;

	public function __construct($expr, $type) {
		parent::__construct(array('expr' => $expr, 'type' => $type));
		$this->validate_class('Expression', 'expr');
		// TODO validate type
	}
}

?>
