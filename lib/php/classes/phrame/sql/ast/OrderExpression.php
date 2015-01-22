<?php

namespace phrame\sql\ast;

class OrderExpression extends Node {

	const ASC = 'ASC';
	const DESC = 'DESC';

	public $expr;
	public $collate;
	public $order;
}

?>
