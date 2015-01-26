<?php

namespace phrame\sql\ast;

/* `BETWEEN` operator.
 *
 * <between-expression> ->
 *   <expression> "BETWEEN" <expression> "AND" <expression>
 */
class BetweenExpression extends Expression {

	public $expr;
	public $min;
	public $max;

	public function __construct($expr, $min, $max) {
		parent::__construct(array(
			'expr' => $expr,
			'min' => $min,
			'max' => $max
		));
		$this->validate_class('Expression', 'expr');
		$this->validate_class('Expression', 'min');
		$this->validate_class('Expression', 'max');
	}
}

?>
