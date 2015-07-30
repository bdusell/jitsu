<?php

namespace jitsu\sql\ast;

/* A `WHEN` clause in a `CASE` expression.
 *
 * <when-clause> ->
 *   "WHEN" <expression> "THEN" <expression>
 */
class WhenClause extends Node {

	public $when;
	public $then;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'when');
		$this->validate_class('Expression', 'then');
	}
}

?>
