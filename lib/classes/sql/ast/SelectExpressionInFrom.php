<?php

namespace phrame\sql\ast;

/* A SELECT expression appearing in a FROM clause.
 *
 * <select-expression-in-from> ->
 *   <select-expression>
 */
class SelectExpressionInFrom extends FromExpression {

	public $select;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('SelectExpression', 'select');
	}
}

?>
