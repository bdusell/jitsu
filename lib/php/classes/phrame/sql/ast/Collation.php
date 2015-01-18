<?php

namespace phrame\sql\ast;

class Collation extends Node {

	const BINARY = 'BINARY';

	public $type;

	public function __construct($type) {
		$this->type = $type;
	}
}

?>
