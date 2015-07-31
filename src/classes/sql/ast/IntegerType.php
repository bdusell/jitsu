<?php

namespace jitsu\sql\ast;

/* SQL integer type. */
class IntegerType extends Type {

	public $bytes;
	public $signed;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('bytes');
		$this->validate_bool('signed');
	}
}

?>
