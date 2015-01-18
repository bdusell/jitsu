<?php

namespace phrame\sql\ast;

class UnaryOperation extends Expression {

	const NEGATE = '-';
	const LOGICAL_NOT = 'NOT';

	public $operator;
	public $expr;

	public function __construct($operator, $expr) {
		$this->operator = $operator;
		$this->expr = $expr;
	}
}

?>
