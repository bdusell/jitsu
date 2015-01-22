<?php

namespace phrame\sql\ast;

class CompoundSelectStatementCore extends SelectStatementCore {

	const UNION = 'UNION';
	const UNION_ALL = 'UNION ALL';

	public $left;
	public $operator;
	public $right;
}

?>
