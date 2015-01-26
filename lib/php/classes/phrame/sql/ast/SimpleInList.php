<?php

namespace phrame\sql\ast;

/* An explicit list of expressions on the right side of an IN operator.
 *
 * <simple-in-list> ->
 *   "(" <expression>+{","} ")"
 */
class SimpleInList extends InList {

	public $exprs;

	public function __construct($exprs) {
		parent::__construct(array('exprs' => $exprs));
		$this->validate_array('Expression', 'exprs');
	}
}

?>
