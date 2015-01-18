<?php

namespace phrame\sql\ast;

class SelectExpression extends Expression {

	public $select;
	public $as;

	public function __construct($select, $as) {
		$this->select = $select;
		$this->as = $as;
	}
}

?>
