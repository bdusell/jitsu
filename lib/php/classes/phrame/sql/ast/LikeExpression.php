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

	public function __construct($expr, $pattern, $escape = null) {
		parent::__construct(array(
			'expr' => $expr,
			'pattern' => $pattern,
			'escape' => $escape
		));
		$this->validate_class('Expression', 'expr');
		$this->validate_class('Expression', 'pattern');
		$this->validate_optional_class('Expression', 'escape');
	}
}

?>
