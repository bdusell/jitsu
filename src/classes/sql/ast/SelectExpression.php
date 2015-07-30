<?php

namespace jitsu\sql\ast;

/* A SELECT statement as an expression, with an optional AS clause.
 *
 * <select-expression> ->
 *   "(" <select-statement> ")" ["AS" <identifier>]
 */
class SelectExpression extends Expression {

	public $select;
	public $as;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('SelectStatement', 'select');
		$this->validate_class('Identifier', 'as');
	}
}

?>
