<?php

namespace jitsu\sql\ast;

/* SQL fixed string type. */
class FixedStringType extends Type {

	public $length;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('length');
	}
}

?>
