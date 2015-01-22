<?php

namespace phrame\sql\visitors;

class MysqlVisitor extends CodeGenerationVisitor {

	public function visitIdentifier($n) {
		return '`' . str_replace('`', '``', $n->value) . '`';
	}
}

?>
