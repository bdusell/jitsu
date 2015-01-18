<?php

namespace phrame\sql\ast;

class WildcardColumnExpression extends ColumnExpression {

	public $table;

	public function __construct($table) {
		$this->table = $table;
	}
}

?>
