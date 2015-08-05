<?php

namespace jitsu\orm;

use jitsu\sql\Ast as sql;

class ModelDefinition {

	private $name;
	private $column_definitions = array();
	private $table_constraints = array();
	private $table_modifiers = array();
	private $primary_key_column_definitions = null;

	public function __construct($name, $defs = null) {
		$this->name = $name;
		if($defs) {
			foreach($defs as $def) {
				$this->add_definition($def);
			}
			if($this->primary_key_column_definitions === null) {
				$this->add_default_primary_key();
			}
		}
	}

	public function name() {
		return $this->name;
	}

	public function create_statement() {
		return sql::create_table(
			sql::table($this->name),
			$this->column_definitions,
			$this->table_constraints,
			$this->table_modifiers
		);
	}

	public function primary_key_column_definitions() {
		return $this->primary_key_column_definitions;
	}

	private function add_definition($def) {
		foreach($def->column_definitions() as $col_def) {
			$this->column_definitions[] = $col_def;
		}
		foreach($def->table_constraints() as $table_constraint) {
			$this->table_constraints[] = $table_constraint;
		}
		foreach($def->table_modifiers() as $table_modifier) {
			$this->table_modifiers[] = $table_modifier;
		}
		$pk_col_defs = $def->primary_key_column_definitions();
		if($pk_col_defs !== null) {
			$this->set_primary_key_column_definitions($pk_col_defs);
		}
	}

	private function set_primary_key_column_definitions($pk_col_defs) {
		if($this->primary_key_column_definitions === null) {
			$this->primary_key_column_definitions = $pk_col_defs;
		} else {
			throw new \InvalidArgumentException(
				'multiple primary key definitions');
		}
	}

	private function add_default_primary_key() {
		$col_def = (new definitions\DefaultPrimaryKey)->column_definition();
		$this->primary_key_column_definitions = array($col_def);
		array_unshift($this->column_definitions, $col_def);
	}
}

?>
