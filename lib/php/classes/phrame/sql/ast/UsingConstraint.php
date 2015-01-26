<?php

namespace phrame\sql\ast;

/* A USING constraint after a JOIN expression.
 *
 * <using-constraint> ->
 *   "USING" "(" <identifier>+{","} ")"
 */
class UsingConstraint extends JoinConstraint {

	public $columns;

	public function __construct($columns) {
		parent::__construct(array('columns' => $columns));
		$this->validate_array('Identifier', 'columns');
	}
}

?>
