<?php

namespace phrame\sql\ast;

/* A VALUES statement, minus the optional ORDER BY and LIMIT clauses.
 *
 * <values-statement> ->
 *   "VALUES" ("(" <expression>+{","} ")")+{","}
 */
class ValuesStatementCore extends SelectStatementCore {

	public $values;

	public function __construct($values) {
		parent::__construct(array('values' => $values));
		$this->validate_array_array('Expression', 'values');
	}
}

?>
