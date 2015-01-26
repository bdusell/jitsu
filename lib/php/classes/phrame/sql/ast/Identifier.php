<?php

namespace phrame\sql\ast;

/* An identifier, such as a column or table name. */
class Identifier extends Node {

	public $value;

	public function __construct($value) {
		parent::__construct(array('value' => $value));
		$this->validate_string('value');
	}
}

?>
