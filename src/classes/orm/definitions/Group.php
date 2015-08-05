<?php

namespace jitsu\orm\definitions;

abstract class Group extends Definition {

	private $col_defs;
	private $table_consts = array();

	public function __construct($defs) {
		$col_defs = self::col_defs($defs);
		if(count($col_defs) === 1) {
			$this->store_original_col_defs($col_defs);
			$col_def = $col_defs[0] = clone $col_defs[0];
			$this->mark_single($col_def);
		} else {
			$this->table_consts[] = $this->wrap_group(self::names($col_defs));
		}
		$this->col_defs = $col_defs;
	}

	public function column_definitions() {
		return $this->col_defs;
	}

	public function table_constraints() {
		return $this->table_consts;
	}

	protected function store_original_col_defs($col_defs) {
	}

	abstract protected function mark_single($col_def);
	abstract protected function wrap_group($names);

	private static function names($col_defs) {
		$r = array();
		foreach($col_defs as $col_def) $r[] = $col_def->name;
		return $r;
	}

	private static function col_defs($defs) {
		$r = array();
		foreach($defs as $def) {
			foreach($def->column_definitions() as $col_def) {
				$r[] = $col_def;
			}
		}
		return $r;
	}
}

?>
