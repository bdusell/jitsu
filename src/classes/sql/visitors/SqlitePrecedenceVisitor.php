<?php

namespace phrame\sql\visitors;

class SqlitePrecedenceVisitor extends Visitor {

	public function visitAdditionExpression($n) {
		return 5;
	}

	public function visitAndExpression($n) {
		return 9;
	}

	public function visitAtomicExpression($n) {
		return 0;
	}

	public function visitBetweenExpression($n) {
		return 8;
	}

	public function visitCaseExpression($n) {
		return 0;
	}

	public function visitCollateExpression($n) {
		return 1;
	}

	public function visitConcatenationExpression($n) {
		return 3;
	}

	public function visitDivisionExpression($n) {
		return 4;
	}

	public function visitEqualityExpression($n) {
		return 8;
	}

	public function visitGreaterThanExpression($n) {
		return 7;
	}

	public function visitGreaterThanOrEqualExpression($n) {
		return 7;
	}

	public function visitInExpression($n) {
		return 8;
	}

	public function visitInequalityExpression($n) {
		return 8;
	}

	public function visitIsExpression($n) {
		return 8;
	}

	public function visitLessThanExpression($n) {
		return 7;
	}

	public function visitLessThanOrEqualExpression($n) {
		return 7;
	}

	public function visitLikeExpression($n) {
		return 8;
	}

	public function visitModulusExpression($n) {
		return 4;
	}

	public function visitMultiplicationExpression($n) {
		return 4;
	}

	public function visitNegationExpression($n) {
		return 2;
	}

	public function visitNotExpression($n) {
		return 2;
	}

	public function visitOrExpression($n) {
		return 10;
	}

	public function visitSubtractionExpression($n) {
		return 5;
	}

	public function visitWhenClause($n) {
		return 0;
	}
}

?>
