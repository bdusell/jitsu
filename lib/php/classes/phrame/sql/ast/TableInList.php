<?php

namespace phrame\sql\ast;

/* A table reference on the right side of an `IN` operator.
 *
 * <table-in-list> ->
 *   <table-reference>
 */
class TableInList extends InList {

	public $table;

	public function __construct($table) {
		parent::__construct(array('table' => $table));
		$this->validate_class('TableReference', 'table');
	}
}

?>
