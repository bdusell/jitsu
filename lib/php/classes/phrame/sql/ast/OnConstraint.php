<?php

namespace phrame\sql\ast;

/* An ON constraint for a JOIN expression.
 *
 * <on-constraint> ->
 *   "ON" <expression>
 */
class OnConstraint extends JoinConstraint {

	public $expr;

	public function __construct($expr) {
		parent::__construct(array('expr' => $expr));
		$this->validate_class('Expression', 'expr');
	}
}

?>
