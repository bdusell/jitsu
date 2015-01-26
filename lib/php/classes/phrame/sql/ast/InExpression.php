<?php

namespace phrame\sql\ast;

/* `IN` operator.
 *
 * <in-expression> ->
 *   <expression> "IN" <in-list>
 */
class InExpression extends Expression {

	public $expr;
	public $in;

	public function __construct($expr, $in) {
		parent::__construct(array('expr' => $expr, 'in' => $in));
		$this->validate_class('Expression', 'expr');
		$this->validate_class('InList', 'in');
	}
}

?>
