<?php

namespace phrame\sql\ast;

/* A `CHECK` table constraint.
 *
 * <check-constraint> ->
 *   "CHECK" "(" <expr> ")"
 */
class CheckConstraint extends TableConstraint {

	public $expr;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'expr');
	}
}

?>
