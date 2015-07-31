<?php

namespace jitsu\sql\ast;

/* SQL variable-length binary string type. */
class ByteStringType extends Type {

	public $maximum_length;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('maximum_length');
	}
}

?>
