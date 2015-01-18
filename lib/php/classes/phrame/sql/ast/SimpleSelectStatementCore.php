<?php

namespace phrame\sql\ast;

class SimpleSelectStatementCore extends SelectStatementCore {

	public $distinct;
	public $columns;
	public $from;
	public $where;
	public $group_by;
	public $having;

	public function __construct($distinct, $columns, $from, $where, $group_by, $having) {
		$this->distinct = $distinct;
		$this->columns = $columns;
		$this->from = $from;
		$this->where = $where;
		$this->group_by = $group_by;
		$this->having = $having;
	}
}

?>
