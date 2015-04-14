<?php

namespace phrame\sql\ast;

/* `LIKE` operator with optional `ESCAPE` clause.
 *
 * <like-expression> ->
 *   <expression> "LIKE" <expression> ["ESCAPE" <expression>]
 */
class LikeExpression extends Expression {

	public $expr;
	public $pattern;
	public $escape;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
		$this->validate_class('Expression', 'pattern');
		$this->validate_optional_class('Expression', 'escape');
	}
}

?>
