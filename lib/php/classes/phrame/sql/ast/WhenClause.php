<?php

namespace phrame\sql\ast;

/* A `WHEN` clause in a `CASE` expression.
 *
 * <when-clause> ->
 *   "WHEN" <expression> "THEN" <expression>
 */
class WhenClause extends Node {

	public $when;
	public $then;

	public function __construct($when, $then) {
		parent::__construct(array('when' => $when, 'then' => $then));
		$this->validate_class('Expression', 'when');
		$this->validate_class('Expression', 'then');
	}
}

?>
