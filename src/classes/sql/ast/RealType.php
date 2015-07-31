<?php

namespace jitsu\sql\ast;

/* SQL real type. */
class RealType extends Type {

	public $bytes;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('bytes');
	}
}

?>
