<?php

namespace jitsu\sql\ast;

/* A column assignment in a SET clause.
 *
 * <assignment> ->
 *   <identifier> "=" <expression>
 */
class Assignment extends Node {

	public $column;
	public $expr;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Identifier', 'column');
		$this->validate_class('Expression', 'expr');
	}
}

?>
