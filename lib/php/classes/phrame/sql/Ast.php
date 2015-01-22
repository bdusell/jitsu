<?php

namespace phrame\sql;

class Ast {

	public static function select(/* $column, ... */) {
		return new ast\SimpleSelectStatementCore(array(
			'distinct' => false,
			'columns' => func_get_args()
		));
	}

	public static function select_distinct(/* $column, ... */) {
		return new ast\SimpleSelectStatementCore(array(
			'distinct' => true,
			'columns' => func_get_args()
		));
	}

	public static function values(/* $value_array, ... */) {
		return new ast\ValuesStatement(array(
			'values' => func_get_args()
		));
	}

	public static function row(/* $expr, ... */) {
		return new ast\ValuesStatement(array(
			'values' => array(func_get_args())
		));
	}

	public static function star() {
		return new ast\WildcardColumnExpression();
	}

	public static function table($name) {
		return new ast\TableReference(array(
			'table' => new ast\Identifier(array(
				'value' => $name
			)),
		));
	}

	public static function col($name) {
		return new ast\ColumnReference(array(
			'column' => new ast\Identifier(array(
				'value' => $name
			))
		));
	}

	public static function value($value = null) {
		if(func_num_args() === 0) {
			return new ast\AnonymousPlaceholder();
		} elseif(is_string($value)) {
			return new ast\StringLiteral(array('value' => $value));
		} elseif(is_int($value)) {
			return new ast\IntegerLiteral(array('value' => $value));
		} elseif(is_real($value)) {
			return new ast\RealLiteral(array('value' => $value));
		} elseif($value === null) {
			return new ast\NullLiteral();
		}
	}
}

?>
