<?php

namespace jitsu\sql\ast;

/* A real number constant.
 *
 * <real-literal> ->
 *   [some real number]
 */
class RealLiteral extends LiteralExpression {

	public $value;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_float('value');
	}
}

?>
