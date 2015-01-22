<?php

namespace phrame\sql\visitors;

class ValidationVisitor extends Visitor {

	public function visitSelectStatement($n) {
		$this->validate_class($n, 'core', 'SelectStatementCore');
		$this->validate_optional_array($n, 'order_by', 'OrderExpression');
		$this->validate_optional_class($n, 'limit', 'Expression');
		$this->validate_optional_class($n, 'offset', 'Expression');
	}

	public function visitCompoundSelectStatementCore($n) {
		$this->validate_class($n, 'left', 'SelectStatementCore');
		$this->validate_const($n, 'operator');
		$this->validate_class($n, 'right', 'SelectStatementCore');
	}

	public function visitSimpleSelectStatementCore($n) {
		$this->validate_bool($n, 'distinct');
		$this->validate_array($n, 'columns', 'ColumnExpression');
		$this->validate_optional_class($n, 'from', 'FromExpression');
		$this->validate_optional_class($n, 'where', 'Expression');
		$this->validate_optional_class($n, 'group_by', 'Expression');
		$this->validate_optional_class($n, 'having', 'Expression');
	}

	public function visitValuesStatement($n) {
		$this->validate_array_array($n, 'values', 'Expression');
	}

	public function visitSimpleColumnExpression($n) {
		$this->validate_class($n, 'expr', 'Expression');
		$this->validate_optional_class($n, 'as', 'Identifier');
	}

	public function visitWildcardColumnExpression($n) {
		$this->validate_optional_class($n, 'table', 'TableReference');
	}

	public function visitJoinExpression($n) {
		$this->validate_class($n, 'left', 'FromExpression');
		$this->validate_const($n, 'operator');
		$this->validate_class($n, 'right', 'FromExpression');
		$this->validate_optional_class($n, 'constraint', 'JoinConstraint');
	}

	public function visitOnConstraint($n) {
		$this->validate_class($n, 'expr', 'Expression');
	}

	public function visitUsingConstraint($n) {
		$this->validate_array($n, 'identifiers', 'Identifier');
	}

	public function visitTableExpression($n) {
		$this->validate_class($n, 'table', 'TableReference');
		$this->validate_optional_class($n, 'as', 'Identifier');
	}

	public function visitTableReference($n) {
		$this->validate_optional_class($n, 'database', 'Identifier');
		$this->validate_class($n, 'table', 'Identifier');
	}

	public function visitFromSelectExpression($n) {
		$this->validate_class($n, 'select', 'SelectExpression');
	}

	public function visitSelectExpression($n) {
		$this->validate_class($n, 'select', 'SelectStatement');
		$this->validate_optional_class($n, 'as', 'Identifier');
	}

	public function visitOrderExpression($n) {
		$this->validate_class($n, 'expr', 'Expression');
		$this->validate_optional_class($n, 'collate', 'Collation');
		$this->validate_const($n, 'order');
	}

	public function visitCollation($n) {
		$this->validate_const($n, 'type');
	}

	public function visitUnaryOperation($n) {
		$this->validate_const($n, 'operator');
		$this->validate_class($n, 'expr', 'Expression');
	}

	public function visitBinaryOperation($n) {
		$this->validate_class($n, 'left', 'Expression');
		$this->validate_const($n, 'operator');
		$this->validate_class($n, 'right', 'Expression');
	}

	public function visitColumnReference($n) {
		$this->validate_optional_class($n, 'table', 'TableReference');
		$this->validate_class($n, 'column', 'Identifier');
	}

	public function visitIntegerLiteral($n) {
		$this->validate_int($n, 'value');
	}

	public function visitRealLiteral($n) {
		$this->validate_float($n, 'value');
	}

	public function visitStringLiteral($n) {
		$this->validate_string($n, 'value');
	}

	public function visitNullLiteral($n) {
	}

	public function visitAnonymousPlaceholder($n) {
	}

	public function visitNamedPlaceholder($n) {
		$this->validate_string($n, 'name');
	}

	public function visitIdentifier($n) {
		$this->validate_string($n, 'value');
	}

	private function full_class_name($name) {
		return 'phrame\\sql\\ast\\' . $name;
	}

	private function validate_class($n, $prop, $class) {
		$value = $n->$prop;
		$full_class = $this->full_class_name($class);
		if(!($value instanceof $full_class)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be of type ' . $full_class
			);
		}
		$value->accept($this);
	}

	private function validate_optional_class($n, $prop, $class) {
		if($n->$prop !== null) {
			$this->validate_class($n, $prop, $class);
		}
	}

	private function validate_array($n, $prop, $class) {
		$value = $n->$prop;
		$full_class = $this->full_class_name($class);
		if(!is_array($value)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be an array of ' . $full_class
			);
		}
		foreach($value as $subvalue) {
			if(!($subvalue instanceof $full_class)) {
				throw new \LogicException(
					get_class($n) . '->' . $prop .
					' must be an array of ' . $full_class
				);
			}
			$subvalue->accept($this);
		}
	}

	private function validate_optional_array($n, $prop, $class) {
		if($n->$prop !== null) {
			$this->validate_array($n, $prop, $class);
		}
	}

	private function validate_array_array($n, $prop, $class) {
		$value = $n->$prop;
		$full_class = $this->full_class_name($class);
		if(!is_array($value)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be an array of arrays of ' .
				$full_class
			);
		}
		foreach($value as $subvalue) {
			if(!is_array($subvalue)) {
				throw new \LogicException(
					get_class($n) . '->' . $prop .
					' must be an array of arrays of ' .
					$full_class
				);
			}
			foreach($subvalue as $subsubvalue) {
				if(!($subsubvalue instanceof $full_class)) {
					throw new \LogicException(
						get_class($n) . '->' . $prop .
						' must be an array of arrays of ' .
						$full_class
					);
				}
				$subsubvalue->accept($this);
			}
		}
	}

	private function validate_const($n, $prop) {
		$this->validate_string($n, $prop);
	}

	private function validate_string($n, $prop) {
		if(!is_string($n->$prop)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be a string'
			);
		}
	}

	private function validate_bool($n, $prop) {
		if(!is_bool($n->$prop)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be a boolean'
			);
		}
	}

	private function validate_int($n, $prop) {
		if(!is_int($n->$prop)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be an integer'
			);
		}
	}

	private function validate_float($n, $prop) {
		if(!is_float($n->$prop)) {
			throw new \LogicException(
				get_class($n) . '->' . $prop .
				' must be a float'
			);
		}
	}
}

?>
