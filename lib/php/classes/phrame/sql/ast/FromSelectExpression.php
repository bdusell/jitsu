<?php

namespace phrame\sql\ast;

class FromSelectExpression extends FromExpression {

	public $select;

	public function __construct($select) {
		$this->select = $select;
	}
}

?>
