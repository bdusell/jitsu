<?php

namespace jitsu\sql\ast;

/* An expression with an optional AS clause.
 *
 * <simple-column-expression> ->
 *   <expression> ["AS" <identifier>]
 */
class SimpleColumnExpression extends ColumnExpression {

	public $expr;
	public $as;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
		$this->validate_optional_class('Identifier', 'as');
	}
}

?>
