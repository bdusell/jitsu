<?php

namespace phrame\sql\ast;

/* A literal string.
 *
 * <string-literal> ->
 *   '[some string of characters]'
 */
class StringLiteral extends LiteralExpression {

	public $value;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_string('value');
	}
}

?>
