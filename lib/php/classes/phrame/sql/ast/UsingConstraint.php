<?php

namespace phrame\sql\ast;

class UsingConstraint extends JoinConstraint {

	public $identifiers;

	public function __construct($identifiers) {
		$this->identifiers = $identifiers;
	}
}

?>
