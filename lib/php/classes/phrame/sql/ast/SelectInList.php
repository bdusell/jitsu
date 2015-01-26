<?php

namespace phrame\sql\ast;

/* A subquery to the right of an `IN` operator.
 *
 * <select-in-list> ->
 *   "(" <select-statement> ")"
 */
class SelectInList extends InList {

	public $select;

	public function __construct($select) {
		parent::__construct(array('select' => $select));
		$this->validate_class('SelectStatement', 'select');
	}
}

?>
