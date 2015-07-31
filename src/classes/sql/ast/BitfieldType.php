<?php

namespace jitsu\sql\ast;

/* SQL bitfield type. */
class BitfieldType extends Type {

	public $width;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('width');
	}
}

?>
