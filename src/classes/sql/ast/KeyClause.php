<?php

namespace jitsu\sql\ast;

/* A primary key or uniqueness constraint.
 *
 * <key-clause> ->
 *   <primary-key-clause> | <unique-clause>
 */
class KeyClause extends Node {

	public function is_primary_key() {
		return false;
	}
}

?>
