<?php

namespace phrame\sql\ast;

class BinaryOperation extends Expression {

	const LOGICAL_AND = 'AND';
	const LOGICAL_OR = 'OR';
	const EQUAL = '=';
	const NOT_EQUAL = '!=';
	const LESS_THAN = '<';
	const GREATER_THAN = '>';
	const LESS_THAN_OR_EQUAL = '<=';
	const GREATER_THAN_OR_EQUAL = '>=';
	const ADD = '+';
	const SUBTRACT = '-';
	const MULTIPLY = '*';
	const DIVIDE = '/';
	const MODULUS = '%';
	const SHIFT_LEFT = '<<';
	const SHIFT_RIGHT = '>>';
	const BITWISE_AND = '&';
	const BITWISE_OR = '|';
	const LIKE = 'LIKE';
	const IS = 'IS';

	public $left;
	public $operator;
	public $right;
}

?>
