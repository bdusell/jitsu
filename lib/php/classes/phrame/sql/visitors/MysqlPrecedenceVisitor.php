<?php

namespace phrame\sql\visitors;

class MysqlPrecedenceVisitor extends Visitor {

	public function visitAdditionExpression($n) {
		return 6;
	}

	public function visitAndExpression($n) {
		return 13;
	}

	public function visitAtomicExpression($n) {
		return 0;
	}

	public function visitBetweenExpression($n) {
		return 11;
	}

	public function visitCaseExpression($n) {
		return 11;
	}

	public function visitCollateExpression($n) {
		return 1;
	}

	public function visitConcatenationExpression($n) {
		return 0;
	}

	public function visitDivisionExpression($n) {
		return 5;
	}

	public function visitEqualityExpression($n) {
		return 10;
	}

	public function visitGreaterThanExpression($n) {
		return 10;
	}

	public function visitGreaterThanOrEqualExpression($n) {
		return 10;
	}

	public function visitInExpression($n) {
		return 10;
	}

	public function visitInequalityExpression($n) {
		return 10;
	}

	public function visitIsExpression($n) {
		return 10;
	}

	public function visitLessThanExpression($n) {
		return 10;
	}

	public function visitLessThanOrEqualExpression($n) {
		return 10;
	}

	public function visitLikeExpression($n) {
		return 10;
	}

	public function visitModulusExpression($n) {
		return 5;
	}

	public function visitMultiplicationExpression($n) {
		return 5;
	}

	public function visitNegationExpression($n) {
		return 3;
	}

	public function visitNotExpression($n) {
		return 12;
	}

	public function visitOrExpression($n) {
		return 15;
	}

	public function visitSubtractionExpression($n) {
		return 6;
	}

	public function visitWhenClause($n) {
		return 11;
	}
}

?>
