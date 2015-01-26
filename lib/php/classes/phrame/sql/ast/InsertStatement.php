<?php

namespace phrame\sql\ast;

/* A complete INSERT statement.
 *
 * <insert-statement> ->
 *   ("INSERT" ["OR" ("REPLACE" | "IGNORE")] | "REPLACE")
 *   "INTO" <table-projection>
 *   (<select-statement> | "DEFAULT" "VALUES")
 */
class InsertStatement extends Statement {

	const INSERT = 'INSERT';
	const REPLACE = 'REPLACE';
	const INSERT_OR_REPLACE = 'INSERT OR REPLACE';
	const INSERT_OR_IGNORE = 'INSERT OR IGNORE';

	public $type;
	public $table;
	public $select;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_const('type');
		$this->validate_class('TableProjection', 'table');
		$this->validate_optional_class('SelectStatement', 'select');
	}
}

?>
