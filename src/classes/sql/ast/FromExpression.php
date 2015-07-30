<?php

namespace jitsu\sql\ast;

/* An expression contained in a FROM clause.
 *
 * <from-expression> ->
 *   <join-expression> |
 *   <table-expression> |
 *   <select-expression-in-from>
 */
abstract class FromExpression extends Node {

	public function join($right) {
		return new JoinExpression(array(
			'left' => $this,
			'operator' => JoinExpression::INNER,
			'right' => $right
		));
	}
}

?>
