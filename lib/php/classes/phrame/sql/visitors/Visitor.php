<?php

namespace phrame\sql\visitors;

abstract class Visitor {

	public abstract function visitSelectStatement($n);
	public abstract function visitCompoundSelectStatementCore($n);
	public abstract function visitSimpleSelectStatementCore($n);
	public abstract function visitValuesStatement($n);
	public abstract function visitSimpleColumnExpression($n);
	public abstract function visitWildcardColumnExpression($n);
	public abstract function visitJoinExpression($n);
	public abstract function visitOnConstraint($n);
	public abstract function visitUsingConstraint($n);
	public abstract function visitTableExpression($n);
	public abstract function visitTableReference($n);
	public abstract function visitFromSelectExpression($n);
	public abstract function visitSelectExpression($n);
	public abstract function visitOrderExpression($n);
	public abstract function visitCollation($n);
	public abstract function visitUnaryOperation($n);
	public abstract function visitBinaryOperation($n);
	public abstract function visitColumnReference($n);
	public abstract function visitIntegerLiteral($n);
	public abstract function visitRealLiteral($n);
	public abstract function visitStringLiteral($n);
	public abstract function visitNullLiteral($n);
	public abstract function visitAnonymousPlaceholder($n);
	public abstract function visitNamedPlaceholder($n);
	public abstract function visitIdentifier($n);
}

?>
