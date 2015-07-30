<?php

namespace jitsu\sql\ast;

/* A complete, executable statement with optional ORDER BY, LIMIT, and OFFSET
 * clauses. */
class LimitedStatement extends Statement {

	public $order_by;
	public $limit;
	public $offset;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_optional_array('OrderedExpression', 'order_by');
		$this->validate_optional_class('Expression', 'limit');
		$this->validate_optional_class('Expression', 'limit');
	}

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
