<?php

namespace phrame\sql\ast;

/* A foreign key table constraint.
 *
 * <foreign-key-constraint> ->
 *   ["CONSTRAINT" <identifier>]
 *   "FOREIGN" "KEY" "(" <identifier>+{","} ")"
 *   <foreign-key-clause>
 */
class ForeignKeyConstraint extends ColumnGroupTableConstraint {

	public $references;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('ForeignKeyClause', 'references');
	}
}

?>
