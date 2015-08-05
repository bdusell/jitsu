<?php

namespace jitsu\sql\ast;

/* SQL text type. */
class TextType extends CharacterStringType {

	public $prefix_size;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('prefix_size');
	}
}

?>
