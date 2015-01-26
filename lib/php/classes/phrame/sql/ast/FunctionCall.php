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

	public function __construct($name, $arguments = null, $attrs = array()) {
		parent::__construct($attrs + array(
			'name' => $name,
			'arguments' => $arguments,
			'distinct' => false
		));
		$this->validate_string('name');
		$this->validate_bool('distinct');
		$this->validate_array('Expression', 'arguments');
	}
}

?>
