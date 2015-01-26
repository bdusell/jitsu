<?php

namespace phrame\sql\ast;

/* A SELECT expression appearing in a FROM clause.
 *
 * <select-expression-in-from> ->
 *   <select-expression>
 */
class SelectExpressionInFrom extends FromExpression {

	public $select;

	public function __construct($select) {
		parent::__construct(array('select' => $select));
		$this->validate_class('SelectExpression', 'select');
	}
}

?>
