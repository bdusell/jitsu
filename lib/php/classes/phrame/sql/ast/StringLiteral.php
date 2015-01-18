<?php

namespace phrame\sql\ast;

class StringLiteral extends Expression {

	public $value;

	public function __construct($value) {
		$this->value = $value;
	}
}

?>
