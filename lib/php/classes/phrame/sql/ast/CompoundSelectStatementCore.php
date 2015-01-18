<?php

namespace phrame\sql\ast;

class CompoundSelectStatementCore extends SelectStatementCore {

	const UNION = 'UNION';
	const UNION_ALL = 'UNION ALL';

	public $left;
	public $operator;
	public $right;

	public function __construct($left, $operator, $right) {
		$this->left = $left;
		$this->operator = $operator;
		$this->right = $right;
	}
}

?>
