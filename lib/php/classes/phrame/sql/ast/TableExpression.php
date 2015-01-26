<?php

namespace phrame\sql\ast;

/* A table reference with an optional AS clause.
 *
 * <table-expression> ->
 *   <table-reference> ["AS" <identifier>]
 */
class TableExpression extends FromExpression {

	public $table;
	public $as;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('TableReference', 'table');
		$this->validate_optional_class('Identifier', 'as');
	}
}

?>
