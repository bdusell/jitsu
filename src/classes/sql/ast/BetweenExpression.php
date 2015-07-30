<?php

namespace jitsu\sql\ast;

/* `BETWEEN` operator.
 *
 * <between-expression> ->
 *   <expression> "BETWEEN" <expression> "AND" <expression>
 */
class BetweenExpression extends Expression {

	public $expr;
	public $min;
	public $max;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
		$this->validate_class('Expression', 'min');
		$this->validate_class('Expression', 'max');
	}
}

?>
