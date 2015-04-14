<?php

namespace phrame\sql\ast;

/* An EXISTS expression.
 *
 * <exists-expression> ->
 *   "EXISTS" "(" <select-statement> ")"
 */
class ExistsExpression extends AtomicExpression {

	public $select;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('SelectStatement', 'select');
	}
}

?>
