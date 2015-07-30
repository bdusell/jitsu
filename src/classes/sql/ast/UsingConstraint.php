<?php

namespace jitsu\sql\ast;

/* A USING constraint after a JOIN expression.
 *
 * <using-constraint> ->
 *   "USING" "(" <identifier>+{","} ")"
 */
class UsingConstraint extends JoinConstraint {

	public $columns;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_array('Identifier', 'columns');
	}
}

?>
