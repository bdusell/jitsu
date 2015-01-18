<?php

namespace phrame\sql\ast;

class OnConstraint extends JoinConstraint {

	public $expr;

	public function __construct($expr) {
		$this->expr = $expr;
	}
}

?>
