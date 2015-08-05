<?php

namespace jitsu\sql\ast;

/* A complete UPDATE statement.
 *
 * <update-statement> ->
 *   "UPDATE"
 *   <table-reference>
 *   "SET" <assignment>+{","}
 *   ["WHERE" <expression>]
 *   ["ORDER" "BY" <ordered-expression>+{","}]
 *   ["LIMIT" <expression> ["OFFSET" <expression>]]
 */
class UpdateStatement extends LimitedStatement {

	public $table;
	public $assignments;
	public $where;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('TableReference', 'table');
		$this->validate_array('Assignment', 'assignments');
		$this->validate_optional_class('Expression', 'where');
	}

	public function where($expr) {
		$this->where = $expr;
		return $this;
	}
}

?>
