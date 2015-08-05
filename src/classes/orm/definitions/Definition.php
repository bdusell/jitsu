<?php

namespace jitsu\orm\definitions;

abstract class Definition {

	public function column_definitions() {
		return array();
	}

	public function table_constraints() {
		return array();
	}

	public function table_modifiers() {
		return array();
	}

	public function primary_key_column_definitions() {
		return null;
	}
}

?>
