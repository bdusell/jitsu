<?php

namespace phrame\sql\visitors;

abstract class CodeGenerationVisitor extends Visitor {

	private $database;

	public function __construct($database) {
		$this->database = $database;
	}

	public function visitSelectStatement($n) {
		$r = $n->core->accept($this);
		if($n->order_by) {
			$r .= ' ORDER BY ' . $this->join($n->order_by);
		}
		if($n->limit) {
			$r .= ' LIMIT ' . $n->limit->accept($this);
			if($n->offset) {
				$r .= ' OFFSET ' . $n->offset->accept($this);
			}
		}
		return $r;
	}

	public function visitCompoundSelectStatementCore($n) {
		return (
			$n->left->accept($this) . ' ' .
			$n->operator . ' ' .
			$n->right->accept($this)
		);
	}

	public function visitSimpleSelectStatementCore($n) {
		$r = 'SELECT';
		if($n->distinct) {
			$r .= ' DISTINCT';
		}
		$r .= ' ' . $this->join($n->columns);
		if($n->from) {
			$r .= ' FROM ' . $n->from->accept($this);
		}
		if($n->where) {
			$r .= ' WHERE ' . $n->where->accept($this);
		}
		if($n->group_by) {
			$r .= ' GROUP BY ' . $this->join($n->group_by);
			if($n->having) {
				$r .= ' HAVING ' . $n->having->accept($this);
			}
		}
		return $r;
	}

	public function visitValuesStatement($n) {
		$value_sets = array();
		foreach($n->values as $value_set) {
			$value_sets[] = '(' . $this->join($value_set) . ')';
		}
		return 'VALUES ' . implode(', ', $value_sets);
	}

	public function visitSimpleColumnExpression($n) {
		$r = $n->expr->accept($this);
		if($n->as) $r .= ' AS ' . $n->as->accept($this);
		return $r;
	}

	public function visitWildcardColumnExpression($n) {
		$r = '';
		if($n->table) $r .= $n->table->accept($this) . '.';
		$r .= '*';
		return $r;
	}

	public function visitJoinExpression($n) {
		$r = (
			$n->left->accept($this) .
			' ' . $n->operator . ' ' .
			$n->right->accept($this)
		);
		if($n->constraint) {
			$r .= ' ' . $n->constraint->accept($this);
		}
		return $r;
	}

	public function visitOnConstraint($n) {
		return 'ON ' . $n->expr->accept($this);
	}

	public function visitUsingConstraint($n) {
		return 'USING (' . $this->join($n->identifiers) . ')';
	}

	public function visitTableExpression($n) {
		$r = $n->table->accept($this);
		if($n->as) {
			$r .= $n->as->accept($this);
		}
		return $r;
	}

	public function visitTableReference($n) {
		$r = '';
		if($n->database) {
			$r .= $n->database->accept($this) . '.';
		}
		$r .= $n->table->accept($this);
		return $r;
	}

	public function visitFromSelectExpression($n) {
		return $n->select->accept($this);
	}

	public function visitSelectExpression($n) {
		$r = '(' . $n->select->accept($this) . ')';
		if($n->as) $r .= ' AS ' . $n->as->accept($this);
		return $r;
	}

	public function visitOrderExpression($n) {
		$r = $n->expr->accept($this);
		if($n->collate) {
			$r .= ' COLLATE ' . $n->collate->accept($this);
		}
		$r .= ' ' . $n->order;
		return $r;
	}

	public function visitCollation($n) {
		return $n->type;
	}

	public function visitUnaryOperation($n) {
		$r = $n->operator;
		if($r === UnaryOperation::LOGICAL_NOT) $r .= ' ';
		$r .= $n->expr->accept($this);
		return $r;
	}

	public function visitBinaryOperation($n) {
		return (
			$n->left->accept($this) . ' ' .
			$n->operator . ' ' .
			$n->right->accept($this)
		);
	}

	public function visitColumnReference($n) {
		$r = '';
		if($n->table) $r .= $n->table->accept($this) . '.';
		$r .= $n->column->accept($this);
		return $r;
	}

	public function visitIntegerLiteral($n) {
		return (string) $n->value;
	}

	public function visitRealLiteral($n) {
		return (string) $n->value;
	}

	public function visitStringLiteral($n) {
		return $this->database->quote($n->value);
	}

	public function visitNullLiteral($n) {
		return 'NULL';
	}

	public function visitAnonymousPlaceholder($n) {
		return '?';
	}

	public function visitNamedPlaceholder($n) {
		return ':' . $n->name;
	}

	public function visitIdentifier($n) {
		return '"' . str_replace('"', '""', $n->value) . '"';
	}

	protected function join($nodes) {
		$parts = array();
		foreach($nodes as $n) $parts[] = $n->accept($this);
		return implode(', ', $parts);
	}
}

?>
