<?php

namespace phrame\sql\ast;

class SelectStatement extends Node {

	public $core;
	public $order_by;
	public $limit;
	public $offset;

	public function __construct($core, $order_by, $limit, $offset) {
		$this->core = $core;
		$this->order_by = $order_by;
		$this->limit = $limit;
		$this->offset = $offset;
	}
}

?>
