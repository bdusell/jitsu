<?php

namespace jitsu\sql\ast;

/* Drop table statement.
 *
 * <drop-table-statement> ->
 *   "DROP" "TABLE" ["IF" "EXISTS"] <table-reference>
 */
class DropTableStatement extends Statement {

	public $if_exists;
	public $table;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_bool('if_exists');
		$this->validate_class('TableReference', 'table');
	}
}

?>
