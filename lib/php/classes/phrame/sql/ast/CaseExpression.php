<?php

namespace phrame\sql\ast;

/* A CASE-WHEN-THEN-ELSE-END expression.
 *
 * <case-expression> ->
 *   "CASE" [<expression>] <when-clause>+ ["ELSE" <expression>] "END"
 */
class CaseExpression extends Expression {

	public $expr;
	public $cases;
	public $else;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_optional_class('Expression', 'expr');
		$this->validate_array('WhenClause', 'cases');
		$this->validate_optional_class('Expression', 'else');
	}
}

?>
