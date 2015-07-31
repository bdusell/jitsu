<?php

namespace jitsu\sql\ast;

/* SQL decimal type. */
class DecimalType extends Type {

	public $digits;
	public $decimals;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('digits');
		$this->validate_int('decimals');
	}
}

?>
