<?php

namespace jitsu\sql\ast;

/* A default value clause.
 *
 * <default-value-clause> ->
 *   "DEFAULT" ( <literal-expr> | "(" <expr> ")" )
 */
class DefaultValueClause extends Node {

	public $value;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_class('Expression', 'value');
	}
}

?>
