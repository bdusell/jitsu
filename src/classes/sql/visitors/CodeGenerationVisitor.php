<?php

namespace jitsu\sql\visitors;

abstract class CodeGenerationVisitor extends Visitor {

	private $database;
	private $ranker;

	public function __construct($database, $ranker) {
		$this->database = $database;
		$this->ranker = $ranker;
	}

	protected function join($nodes) {
		$parts = array();
		foreach($nodes as $n) $parts[] = $n->accept($this);
		return implode(', ', $parts);
	}

	protected function prec($n) {
		return $n->accept($this->ranker);
	}

	protected function parens($n, $attr) {
		$subn = $n->$attr;
		$r = $subn->accept($this);
		if($this->prec($n) <= $this->prec($subn)) {
			$r = '(' . $r . ')';
		}
		return $r;
	}

	private function unary($n, $op) {
		return $op . $this->parens($n, 'expr');
	}

	private function binary($n, $op) {
		return (
			$this->parens($n, 'left') .
			' ' . $op . ' ' .
			$this->parens($n, 'right')
		);
	}

	public function visitAdditionExpression($n) {
		return $this->binary($n, '+');
	}

	public function visitAndExpression($n) {
		return $this->binary($n, 'AND');
	}

	public function visitAnonymousPlaceholder($n) {
		return '?';
	}

	public function visitAssignment($n) {
		return (
			$n->column->accept($this) .
			' = ' .
			$n->expr->accept($this)
		);
	}

	public function visitBetweenExpression($n) {
		return (
			$this->parens($n, 'expr') .
			' BETWEEN ' .
			$this->parens($n, 'min') .
			' AND ' .
			$this->parens($n, 'max')
		);
	}

	public function visitCaseExpression($n) {
		$r = 'CASE';
		if($n->expr) {
			$r .= ' ' . $this->parens($n, 'expr');
		}
		foreach($n->cases as $case) {
			$r .= ' ' . $case->accept($this);
		}
		if($n->else) {
			$r .= ' ELSE ' . $this->parens($n, 'else');
		}
		$r .= ' END';
		return $r;
	}

	public function visitCastExpression($n) {
		return (
			'CAST (' . $n->expr->accept($this) .
			' AS ' . $n->type->accept($this) . ')'
		);
	}

	public function visitCollateExpression($n) {
		return (
			$this->parens($n, 'expr') .
			' COLLATE ' .
			$n->collation
		);
	}

	public function visitColumnReference($n) {
		$r = '';
		if($n->table) $r .= $n->table->accept($this) . '.';
		$r .= $n->column->accept($this);
		return $r;
	}

	public function visitCompoundSelectStatementCore($n) {
		return (
			$n->left->accept($this) . ' ' .
			$n->operator . ' ' .
			$n->right->accept($this)
		);
	}

	public function visitConcatenationExpression($n) {
		return $this->binary($n, '||');
	}

	public function visitDeleteStatement($n) {
		$r = 'DELETE FROM ' . $n->table->accept($this);
		if($n->where) {
			$r .= ' WHERE ' . $n->where->accept($this);
		}
		$r .= ' ' . $this->visitLimitedStatement($n);
		return $r;
	}

	public function visitDivisionExpression($n) {
		return $this->binary($n, '/');
	}

	public function visitEqualityExpression($n) {
		return $this->binary($n, '=');
	}

	public function visitExistsExpression($n) {
		return (
			'EXISTS (' .
			$n->select->accept($this) .
			')'
		);
	}

	public function visitFunctionCall($n) {
		$r = $n->name . '(';
		if($n->arguments === null) {
			$r .= '*';
		} else {
			if($r->distinct && count($n->arguments) > 0) {
				$r .= 'DISTINCT ';
			}
			$r .= $this->join($n->arguments);
		}
		$r .= ')';
		return $r;
	}

	public function visitGreaterThanExpression($n) {
		return $this->binary($n, '>');
	}

	public function visitGreaterThanOrEqualExpression($n) {
		return $this->binary($n, '>=');
	}

	public function visitIdentifier($n) {
		return '"' . str_replace('"', '""', $n->value) . '"';
	}

	public function visitInExpression($n) {
		return (
			$this->parens($n, 'expr') .
			' IN ' .
			$n->in->accept($this)
		);
	}

	public function visitInequalityExpression($n) {
		return $this->binary($n, '!=');
	}

	public function insertCommand($type) {
		return $type;
	}

	public function visitInsertStatement($n) {
		return (
			$this->insertCommand($n->type) . ' INTO ' .
			$n->table->accept($this) . ' ' .
			$n->select->accept($this)
		);
	}

	public function visitIntegerLiteral($n) {
		return (string) $n->value;
	}

	public function visitIsExpression($n) {
		return $this->binary($n, 'IS');
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

	public function visitLessThanExpression($n) {
		return $this->binary($n, '<');
	}

	public function visitLessThanOrEqualExpression($n) {
		return $this->binary($n, '<=');
	}

	public function visitLikeExpression($n) {
		$r = (
			$this->parens($n, 'left') .
			' LIKE ' .
			$this->parens($n, 'right')
		);
		if($n->escape) {
			$r .= ' ESCAPE ' . $this->parens($n, 'escape');
		}
		return $r;
	}

	public function visitLimitedStatement($n) {
		$r = '';
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

	public function visitMultiplicationExpression($n) {
		return $this->binary($n, '*');
	}

	public function visitNamedPlaceholder($n) {
		return ':' . $n->name;
	}

	public function visitNegationExpression($n) {
		return $this->unary($n, '-');
	}

	public function visitNotExpression($n) {
		return $this->unary($n, 'NOT ');
	}

	public function visitNullLiteral($n) {
		return 'NULL';
	}

	public function visitOnConstraint($n) {
		return 'ON ' . $n->expr->accept($this);
	}

	public function visitOrExpression($n) {
		return $this->binary($n, 'OR');
	}

	public function visitOrderedExpression($n) {
		return (
			$n->expr->accept($this) . ' ' .
			$n->order
		);
	}

	public function visitRealLiteral($n) {
		return (string) $n->value;
	}

	public function visitSelectExpression($n) {
		return (
			'(' . $n->select->accept($this) .
			') AS ' .
			$n->as->accept($this)
		);
	}

	public function visitSelectExpressionInFrom($n) {
		return $n->select->accept($this);
	}

	public function visitSelectInList($n) {
		return $n->select->accept($this);
	}

	public function visitSelectStatement($n) {
		return (
			$n->core->accept($this) .
			$this->visitLimitedStatement($n)
		);
	}

	public function visitSimpleColumnExpression($n) {
		$r = $n->expr->accept($this);
		if($n->as) $r .= ' AS ' . $n->as->accept($this);
		return $r;
	}

	public function visitSimpleInList($n) {
		return '(' . $this->join($n->exprs) . ')';
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

	public function visitStringLiteral($n) {
		return $this->database->quote($n->value);
	}

	public function visitSubtractionExpression($n) {
		return $this->binary($n, '-');
	}

	public function visitTableExpression($n) {
		$r = $n->table->accept($this);
		if($n->as) {
			$r .= $n->as->accept($this);
		}
		return $r;
	}

	public function visitTableInList($n) {
		return $n->table->accept($this);
	}

	public function visitTableProjection($n) {
		return (
			$n->table->accept($this) . '(' .
			$this->join($n->columns) . ')'
		);
	}

	public function visitTableReference($n) {
		$r = '';
		if($n->database) {
			$r .= $n->database->accept($this) . '.';
		}
		$r .= $n->table->accept($this);
		return $r;
	}

	public function visitUpdateStatement($n) {
		$r = (
			$n->type . ' ' .
			$n->table->accept($this) . ' ' .
			$this->join($n->assignments)
		);
		if($n->where) {
			$r .= $n->where->accept($this);
		}
		$r .= $this->visitLimitedStatement($n);
		return $r;
	}

	public function visitUsingConstraint($n) {
		return 'USING (' . $this->join($n->columns) . ')';
	}

	public function visitValuesStatementCore($n) {
		$value_sets = array();
		foreach($n->values as $value_set) {
			$value_sets[] = '(' . $this->join($value_set) . ')';
		}
		return 'VALUES ' . implode(', ', $value_sets);
	}

	public function visitWhenClause($n) {
		return (
			'WHEN ' . $this->parens($n, 'when') .
			' THEN ' . $this->parens($n, 'then')
		);
	}

	public function visitWildcardColumnExpression($n) {
		$r = '';
		if($n->table) $r .= $n->table->accept($this) . '.';
		$r .= '*';
		return $r;
	}

	public function visitCreateTableStatement($n) {
		$r = 'CREATE ';
		if($n->temporary) $r .= 'TEMPORARY ';
		$r .= 'TABLE ';
		if($n->if_not_exists) $r .= 'IF NOT EXISTS ';
		$r .= (
			$n->name->accept($this) .
			' (' . $this->join(array_merge(
				$n->columns,
				$n->constraints
			)) . ')'
		);
		if($n->modifiers) {
			$r .= ' ' . $this->join($n->modifiers);
		}
		return $r;
	}

	public function visitColumnDefinition($n) {
		$r = $n->name->accept($this) . ' ' . $n->type->accept($this);
		if($n->not_null) $r .= ' ' . $n->not_null->accept($this);
		if($n->default) $r .= ' ' . $n->default->accept($this);
		if($n->autoincrement) $r .= ' ' . $n->autoincrement->accept($this);
		if($n->key) $r .= ' ' . $n->key->accept($this);
		if($n->foreign_key) $r .= ' ' . $n->foreign_key->accept($this);
		return $r;
	}

	public function visitNotNullClause($n) {
		return 'NOT NULL';
	}

	public function visitDefaultValueClause($n) {
		$expr = $n->value->accept($this);
		if(!($n->value instanceof \jitsu\sql\ast\LiteralExpression)) {
			$expr = '(' . $expr . ')';
		}
		return 'DEFAULT ' . $expr;
	}

	public function visitAutoincrementClause($n) {
		return 'AUTOINCREMENT';
	}

	public function visitPrimaryKeyClause($n) {
		return 'PRIMARY KEY';
	}

	public function visitUniqueClause($n) {
		return 'UNIQUE';
	}

	private function constraintClause($n, $text) {
		$r = '';
		if($n->name) $r .= 'CONSTRAINT ' . $n->name->accept($this) . ' ';
		$r .= $text . ' (' . $this->join($n->columns) . ')';
		return $r;
	}

	public function visitPrimaryKeyConstraint($n) {
		return $this->constraintClause($n, 'PRIMARY KEY');
	}

	public function visitIndexConstraint($n) {
		$r = 'INDEX ';
		if($n->name) $r .= $n->name->accept($this) . ' ';
		$r .= '(' . $this->join($n->columns) . ')';
		return $r;
	}

	public function visitUniqueConstraint($n) {
		return $this->constraintClause($n, 'UNIQUE');
	}

	public function visitForeignKeyConstraint($n) {
		return (
			$this->constraintClause($n, 'FOREIGN KEY') .
			' ' . $n->references($this)
		);
	}

	public function visitCheckConstraint($n) {
		return 'CHECK (' . $n->expr->accept($this) . ')';
	}

	public function visitForeignKeyClause($n) {
		$r = 'REFERENCES ' . $n->table;
		if($n->columns) $r .= '(' . $this->join($n->columns) . ')';
		if($n->on_delete !== null) $r .= ' ON DELETE ' . $n->on_delete;
		if($n->on_update !== null) $r .= ' ON UPDATE ' . $n->on_update;
		if($n->deferrable !== null) {
			if(!$n->deferrable) $r .= ' NOT';
			$r .= ' DEFERRABLE';
		}
		if($n->initially !== null) $r .= ' INITIALLY ' . $n->initially;
		return $r;
	}

	abstract public function visitIntegerType($n);
}

?>
