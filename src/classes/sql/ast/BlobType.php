<?php

namespace jitsu\sql\ast;

/* SQL binary blob type. */
class BlobType extends Type {

	public $prefix_size;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_int('prefix_size');
	}
}

?>
