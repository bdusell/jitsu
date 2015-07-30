<?php

namespace jitsu\sql\ast;

/* An arbitrary expression.
 *
 * <expression> ->
 *   <atomic-expression> |
 *   <collate-expression> |
 *   <unary-operator-expression> |
 *   <binary-operator-expression> |
 *   <in-expression> |
 *   <like-expression> |
 *   <between-expression> |
 *   <case-expression>
 */
abstract class Expression extends Node {

	public function as_self() {
		return new SimpleColumnExpression(array(
			'expr' => $this
		));
	}

	public function as_name($name) {
		return new SimpleColumnExpression(array(
			'expr' => $this,
			'as' => new Identifier($name)
		));
	}

	public function asc() {
		return new OrderedExpression(array(
			'expr' => $this,
			'order' => OrderedExpression::ASC
		));
	}

	public function desc() {
		return new OrderedExpression(array(
			'expr' => $this,
			'order' => OrderedExpression::DESC
		));
	}

	public function eq($expr) {
		return new EqualityExpression(array(
			'left' => $this,
			'right' => $expr
		));
	}

	public function in($expr) {
		if(is_array($expr)) {
			$expr = new SimpleInList(array(
				'exprs' => $expr
			));
		}
		return new InExpression(array(
			'expr' => $this,
			'in' => $expr
		));
	}

	public function and_($expr) {
		return new AndExpression(array(
			'left' => $this,
			'right' => $expr
		));
	}
}

?>
