<?php

namespace phrame\sql\ast;

/* An expression with a COLLATE clause.
 *
 * <collate-expression> ->
 *   <expression> "COLLATE" [collation name]
 */
class CollateExpression extends Expression {

	const BINARY = 'BINARY';

	public $collation;

	public function __construct($expr, $collation) {
		parent::__construct(array(
			'expr' => $expr,
			'collation' => $collation
		));
		$this->validate_class('Expression', 'expr');
		$this->validate_const('collation');
	}
}

?>
