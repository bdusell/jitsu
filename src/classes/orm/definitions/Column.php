<?php

namespace jitsu\orm\definitions;

class Column extends Definition {

	private $col_def;
	private $pk_defs;

	public function __construct($col_def) {
		$this->col_def = $col_def;
		$this->pk_defs = $col_def->is_primary_key() ? array($col_def) : null;
	}

	public function column_definitions() {
		return array($this->col_def);
	}

	public function column_definition() {
		return $this->col_def;
	}

	public function primary_key_column_definitions() {
		return $this->pk_defs;
	}
}

?>
