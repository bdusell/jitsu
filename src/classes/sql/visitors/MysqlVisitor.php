<?php

namespace jitsu\sql\visitors;

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

	public function insertCommand($type) {
		switch($type) {
		case \jitsu\sql\ast\InsertStatement::INSERT_OR_REPLACE:
			return 'INSERT ON DUPLICATE KEY UPDATE';
		case \jitsu\sql\ast\InsertStatement::INSERT_OR_IGNORE:
			return 'INSERT IGNORE';
		default:
			return $type;
		}
	}
}

?>
