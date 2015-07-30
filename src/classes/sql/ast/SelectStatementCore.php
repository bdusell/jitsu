<?php

namespace jitsu\sql\ast;

/* The core of a SELECT or VALUES statement, consisting of the part which does
 * not contain an ORDER or LIMIT clause.
 *
 * <select-statement-core> ->
 *   <compound-select-statment-core> |
 *   <simple-select-statment-core> |
 *   <values-statement-core>
 */
abstract class SelectStatementCore extends Node {

	public function order_by(/* $expr, ... */) {
		return new SelectStatement(array(
			'core' => $this,
			'order_by' => func_get_args()
		));
	}

	public function limit($expr) {
		return new SelectStatement(array(
			'core' => $this,
			'limit' => $expr
		));
	}

	public function offset($expr) {
		return new SelectStatement(array(
			'core' => $this,
			'offset' => $expr
		));
	}

	public function union($select_core) {
		return new CompoundSelectStatementCore(array(
			'left' => $this,
			'operator' => CompoundSelectStatementCore::UNION,
			'right' => $select_core
		));
	}

	public function union_all($select_core) {
		return new CompoundSelectStatementCore(array(
			'left' => $this,
			'operator' => CompoundSelectStatementCore::UNION_ALL,
			'right' => $select_core
		));
	}
}

?>
