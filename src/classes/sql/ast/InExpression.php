<?php

namespace jitsu\sql\ast;

/* `IN` operator.
 *
 * <in-expression> ->
 *   <expression> "IN" <in-list>
 */
class InExpression extends Expression {

	public $expr;
	public $in;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
		$this->validate_class('InList', 'in');
	}
}

?>
