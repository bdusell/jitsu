<?php

namespace jitsu\orm\definitions;

class PrimaryKey extends Group {

	private $original_defs;

	protected function store_original_col_defs($col_defs) {
		$this->original_defs = $col_defs;
	}

	public function primary_key_column_definitions() {
		return $this->original_defs;
	}

	protected function mark_single($def) {
		$def->primary_key();
	}

	protected function wrap_group($names) {
		return new \jitsu\sql\ast\PrimaryKeyConstraint(array(
			'columns' => $names
		));
	}
}

?>
