<?php

namespace phrame\sql\ast;

/* A literal string.
 *
 * <string-literal> ->
 *   '[some string of characters]'
 */
class StringLiteral extends LiteralExpression {

	public $value;

	public function __construct($value) {
		parent::__construct(array('value' => $value));
		$this->validate_string('value');
	}
}

?>
