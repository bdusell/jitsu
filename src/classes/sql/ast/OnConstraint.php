<?php

namespace jitsu\sql\ast;

/* An ON constraint for a JOIN expression.
 *
 * <on-constraint> ->
 *   "ON" <expression>
 */
class OnConstraint extends JoinConstraint {

	public $expr;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
	}
}

?>
