<?php

namespace phrame\sql\ast;

class SimpleColumnExpression extends ColumnExpression {

	public $expr;
	public $as;

	public function __construct($expr, $as) {
		$this->expr = $expr;
		$this->as = $as;
	}
}

?>
