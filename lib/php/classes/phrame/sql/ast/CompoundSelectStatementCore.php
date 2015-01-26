<?php

namespace phrame\sql\ast;

/* Two SELECT or VALUES statement cores connected by a UNION operator.
 *
 * <compound-select-statement-core> ->
 *   <select-statement-core> "UNION" ["ALL"] <select-statement-core>
 */
class CompoundSelectStatementCore extends SelectStatementCore {

	const UNION = 'UNION';
	const UNION_ALL = 'UNION ALL';

	public $left;
	public $operator;
	public $right;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'left');
		$this->validate_const('operator');
		$this->validate_class('Expression', 'right');
	}
}

?>
