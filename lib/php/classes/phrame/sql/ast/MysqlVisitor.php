<?php

namespace phrame\sql\ast;

class MysqlVisitor extends Visitor {

	public function visitIdentifier($n) {
		return '`' . str_replace('`', '``', $n->value) . '`';
	}
}

?>
