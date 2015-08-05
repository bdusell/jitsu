<?php

namespace jitsu\sql\ast;

/* SQL variable-length string type. */
class StringType extends CharacterStringType {

	public $maximum_length;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('maximum_length');
	}
}

?>
