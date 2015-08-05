<?php

namespace jitsu\orm\definitions;

use jitsu\sql\Ast as sql;

class ForeignKey extends Definition {

	private $col_defs;
	private $table_consts = array();

	public function __construct($name, $defs) {
		$this->col_defs = $defs;
		$names = self::names($defs);
		if(count($defs) > 1) {
			$this->table_consts[] = new \jitsu\ast\ForeignKeyConstraint(array(
				'columns' => $names,
				'references' => self::fk_clause($name, $names)
			));
		} else {
			$def = $this->col_defs[0] = clone $this->col_defs[0];
			if($def->name->value === 'id') {
				$def->name = sql::name($name . '_id');
			}
			$def->foreign_key(self::fk_clause($name, $names));
		}
	}

	private static function names($defs) {
		$r = array();
		foreach($defs as $def) {
			$r[] = $def->name;
		}
		return $r;
	}

	private static function fk_clause($name, $names) {
		return new \jitsu\sql\ast\ForeignKeyClause(array(
			'table' => sql::table($name),
			'columns' => $names
		));
	}

	public function column_definitions() {
		return $this->col_defs;
	}

	public function table_constraints() {
		return $this->table_consts;
	}
}

?>
