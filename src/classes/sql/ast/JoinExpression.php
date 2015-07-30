<?php

namespace jitsu\sql\ast;

/* Two FROM clause expressions joined by a JOIN operator.
 *
 * <join-expression> ->
 *   <from-expression>
 *   ["INNER" | "LEFT OUTER" | ...] "JOIN"
 *   <from-expression>
 *   [<join-constraint>]
 */
class JoinExpression extends FromExpression {

	const INNER = 'JOIN';
	const LEFT_OUTER = 'LEFT OUTER JOIN';
	const RIGHT_OUTER = 'RIGHT OUTER JOIN';
	const FULL_OUTER = 'FULL OUTER JOIN';
	const CROSS = 'CROSS JOIN';

	public $left;
	public $operator;
	public $right;
	public $constraint;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('FromExpression', 'left');
		$this->validate_const('operator');
		$this->validate_class('FromExpression', 'right');
		$this->validate_optional_class('JoinConstraint', 'constraint');
	}

	public function on($expr) {
		$this->constraint = new OnConstraint(array(
			'expr' => $expr
		));
		return $this;
	}
}

?>
