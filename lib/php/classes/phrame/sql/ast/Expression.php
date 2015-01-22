<?php

namespace phrame\sql\ast;

abstract class Expression extends Node {

	public function as_self() {
		return new SimpleColumnExpression(array(
			'expr' => $this
		));
	}

	public function as_name($name) {
		return new SimpleColumnExpression(array(
			'expr' => $this,
			'as' => new Identifier($name)
		));
	}

	public function asc($collation = null) {
		return new OrderExpression(array(
			'expr' => $this,
			'order' => OrderExpression::ASC
		));
	}

	public function desc($collation = null) {
		return new OrderExpression(array(
			'expr' => $this,
			'order' => OrderExpression::DESC
		));
	}

	public function eq($expr) {
		return new BinaryOperation(array(
			'left' => $this,
			'operator' => BinaryOperation::EQUAL,
			'right' => $expr
		));
	}
}

?>
