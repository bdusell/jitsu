<?php

namespace jitsu\orm\definitions;

use \jitsu\sql\Ast as sql;

class DefaultPrimaryKey extends Definition {

	private $none = false;
	private $name;
	private $type;
	private $not_null = true;
	private $autoincrement = true;

	public function column_definitions() {
		return $this->none ? array() : array($this->column_definition());
	}

	private function col_def_with_key($key) {
		return new \jitsu\sql\ast\ColumnDefinition(array(
			'name' => sql::name(
				$this->name === null ?
				'id' :
				$this->name
			),
			'type' => (
				$this->type ?
				$this->type :
				sql::int_type(4, false)
			),
			'not_null' => (
				$this->not_null ?
				sql::not_null() :
				null
			),
			'autoincrement' => (
				$this->autoincrement ?
				sql::autoincrement() :
				null
			),
			'key' => $key
		));
	}

	public function column_definition() {
		return $this->col_def_with_key(sql::primary_key());
	}

	public function primary_key_column_definitions() {
		return $this->none ? array() : array($this->col_def_with_key(null));
	}

	public function none() {
		$this->none = true;
		return $this;
	}

	public function name($name) {
		$this->name = $name;
		return $this;
	}

	public function type($type) {
		$this->type = $type;
		return $this;
	}

	public function nullable() {
		$this->not_null = false;
		return $this;
	}

	public function no_autoincrement() {
		$this->autoincrement = false;
		return $this;
	}
}

?>
