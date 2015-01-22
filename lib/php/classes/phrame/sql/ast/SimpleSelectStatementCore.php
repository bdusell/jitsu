<?php

namespace phrame\sql\ast;

class SimpleSelectStatementCore extends SelectStatementCore {

	public $columns;
	public $from;
	public $where;
	public $group_by;
	public $having;

	public function from($from) {
		$this->from = $from;
		return $this;
	}

	public function where($where) {
		$this->where = $where;
		return $this;
	}

	public function group_by(/* $expr, ... */) {
		$this->group_by = func_get_args();
		return $this;
	}

	public function having($having) {
		$this->having = $having;
		return $this;
	}
}

?>
