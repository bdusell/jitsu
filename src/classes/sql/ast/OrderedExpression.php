<?php

namespace jitsu\sql\ast;

/* An expression with an ASC or DESC qualifier.
 *
 * <ordered-expression> ->
 *   <expression> ["ASC" | "DESC"]
 */
class OrderedExpression extends Node {

	const ASC = 'ASC';
	const DESC = 'DESC';

	public $expr;
	public $order;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
		$this->validate_const('order');
	}
}

?>
