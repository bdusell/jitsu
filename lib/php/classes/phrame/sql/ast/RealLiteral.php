<?php

namespace phrame\sql\ast;

/* A real number constant.
 *
 * <real-literal> ->
 *   [some real number]
 */
class RealLiteral extends LiteralExpression {

	public $value;

	public function __construct($value) {
		parent::__construct(array('value' => $value));
		$this->validate_float('value');
	}
}

?>
