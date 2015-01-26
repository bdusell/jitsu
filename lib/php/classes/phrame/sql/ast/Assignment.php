<?php

namespace phrame\sql\ast;

/* A column assignment in a SET clause.
 *
 * <assignment> ->
 *   <identifier> "=" <expression>
 */
class Assignment extends Node {

	public $column;
	public $expr;

	public function __construct($column, $expr) {
		parent::__construct(array('column' => $column, 'expr' => $expr));
		$this->validate_class('ColumnReference', 'column');
		$this->validate_class('Expression', 'expr');
	}
}

?>
