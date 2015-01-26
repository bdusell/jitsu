<?php

namespace phrame\sql\ast;

/* A SELECT statement as an expression, with an optional AS clause.
 *
 * <select-expression> ->
 *   "(" <select-statement> ")" ["AS" <identifier>]
 */
class SelectExpression extends Expression {

	public $select;
	public $as;

	public function __construct($select, $as) {
		parent::__construct(array('select' => $select, 'as' => $as));
		$this->validate_class('SelectStatement', 'select');
		$this->validate_class('Identifier', 'as');
	}
}

?>
