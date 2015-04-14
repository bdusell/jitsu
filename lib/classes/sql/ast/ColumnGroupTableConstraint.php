<?php

namespace phrame\sql\ast;

/* A table constraint with an optional name and a list of column names. */
class ColumnGroupTableConstraint extends TableConstraint {

	public $name;
	public $columns;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_optional_class('Identifier', 'name');
		$this->validate_array('Identifier', 'columns');
	}
}

?>
