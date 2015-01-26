<?php

namespace phrame\sql\ast;

/* An EXISTS expression.
 *
 * <exists-expression> ->
 *   "EXISTS" "(" <select-statement> ")"
 */
class ExistsExpression extends AtomicExpression {

	public $select;

	public function __construct($select) {
		parent::__construct(array('select' => $select));
		$this->validate_class('SelectStatement', 'select');
	}
}

?>
