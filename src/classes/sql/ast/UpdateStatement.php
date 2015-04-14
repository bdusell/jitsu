<?php

namespace phrame\sql\ast;

/* A complete UPDATE statement.
 *
 * <update-statement> ->
 *   "UPDATE" ["OR" ("REPLACE" | "IGNORE")]
 *   <table-reference>
 *   "SET" <assignment>+{","}
 *   ["WHERE" <expression>]
 *   ["ORDER" "BY" <ordered-expression>+{","}]
 *   ["LIMIT" <expression> ["OFFSET" <expression>]]
 */
class UpdateStatement extends LimitedStatement {

	const UPDATE = 'UPDATE';
	const UPDATE_OR_REPLACE = 'UPDATE OR REPLACE';
	const UPDATE_OR_IGNORE = 'UPDATE OR IGNORE';

	public $type;
	public $table;
	public $assignments;
	public $where;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_const('type');
		$this->validate_class('TableReference', 'table');
		$this->validate_optional_class('Expression', 'where');
	}
}

?>
