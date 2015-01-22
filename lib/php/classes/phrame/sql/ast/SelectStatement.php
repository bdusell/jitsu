<?php

namespace phrame\sql\ast;

class SelectStatement extends Node {

	public $order_by;
	public $limit;
	public $offset;

	public function order_by(/* $expr, ... */) {
		$this->order_by = func_get_args();
		return $this;
	}

	public function limit($expr) {
		$this->limit = $expr;
		return $this;
	}

	public function offset($expr) {
		$this->offset = $expr;
		return $this;
	}
}

?>
