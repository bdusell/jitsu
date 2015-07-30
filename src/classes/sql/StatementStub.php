<?php

namespace jitsu\sql;

class StatementStub {

	private $affected_rows;

	public function __construct($affected_rows) {
		$this->affected_rows = $affected_rows;
	}

	public function affected_rows() {
		return $this->affected_rows;
	}
}

?>
