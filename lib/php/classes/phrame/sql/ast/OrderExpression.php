<?php

namespace phrame\sql\ast;

class OrderExpression extends Node {

	const ASC = 'ASC';
	const DESC = 'DESC';

	public $expr;
	public $collate;
	public $order;

	public function __construct($expr, $collate, $order) {
		$this->expr = $expr;
		$this->collate = $collate;
		$this->order = $order;
	}
}

?>
