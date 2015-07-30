<?php

namespace jitsu\sql\ast;

/* An integer constant.
 *
 * <integer-literal> ->
 *   [some integer]
 */
class IntegerLiteral extends LiteralExpression {

	public $value;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('value');
	}
}

?>
