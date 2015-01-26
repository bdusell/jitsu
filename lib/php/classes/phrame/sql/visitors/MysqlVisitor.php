<?php

namespace phrame\sql\visitors;

class MysqlVisitor extends CodeGenerationVisitor {

	public function __construct($database) {
		parent::__construct($database, new MysqlPrecedenceVisitor());
	}

	public function visitIdentifier($n) {
		return '`' . str_replace('`', '``', $n->value) . '`';
	}

	public function visitConcatenationExpression($n) {
		return (
			'CONCAT(' . $n->left->accept($this) . ', ' .
			$n->right->accept($this) . ')'
		);
	}
}

?>
