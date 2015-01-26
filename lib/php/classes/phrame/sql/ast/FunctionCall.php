<?php

namespace phrame\sql\ast;

/* A scalar or aggregate function call.
 *
 * <function-call> ->
 *   [function name] "(" ["*" | ["DISTINCT"] <expression>+{","}] ")"
 */
class FunctionCall extends AtomicExpression {

	public $name;
	public $distinct;
	public $arguments;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_string('name');
		$this->validate_bool('distinct');
		$this->validate_array('Expression', 'arguments');
	}
}

?>
