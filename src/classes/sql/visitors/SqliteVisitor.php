<?php

namespace jitsu\sql\visitors;

class SqliteVisitor extends CodeGenerationVisitor {

	public function __construct($database) {
		parent::__construct($database, new SqlitePrecedenceVisitor());
	}
}

?>
