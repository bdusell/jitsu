<?php

namespace phrame\sql\ast;

/* A complete DELETE statement.
 *
 * <delete-statement> ->
 *   "DELETE" "FROM" <table-reference>
 *   ["WHERE" <expression>]
 *   ["ORDER" "BY" <ordered-expression>+{","}]
 *   ["LIMIT" <expression> ["OFFSET" <expression>]]
 */
class DeleteStatement extends LimitedStatement {

	public $table;
	public $where;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('TableReference', 'table');
		$this->validate_optional_class('Expression', 'where');
	}

	public function where($expr) {
		$this->where = $expr;
		return $this;
	}
}

?>
