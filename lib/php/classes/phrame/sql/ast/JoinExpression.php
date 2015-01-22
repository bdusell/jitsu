<?php

namespace phrame\sql\ast;

class JoinExpression extends FromExpression {

	const INNER = 'INNER JOIN';
	const LEFT_OUTER = 'LEFT OUTER JOIN';
	const RIGHT_OUTER = 'RIGHT OUTER JOIN';
	const FULL_OUTER = 'FULL OUTER JOIN';
	const CROSS = 'CROSS JOIN';

	public $left;
	public $operator;
	public $right;
	public $constraint;
}

?>
