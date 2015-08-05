<?php

namespace jitsu\sql\ast;

/* A primary key clause.
 *
 * <primary-key-clause> ->
 *   "PRIMARY" "KEY"
 */
class PrimaryKeyClause extends KeyClause {

	public function is_primary_key() {
		return true;
	}
}

?>
