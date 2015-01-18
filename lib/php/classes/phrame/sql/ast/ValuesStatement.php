<?php

namespace phrame\sql\ast;

class ValuesStatement extends SelectStatementCore {

	public $values;

	public function __construct($values) {
		$this->values = $values;
	}
}

?>
