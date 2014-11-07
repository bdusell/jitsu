<?php

/* SQL table class. */

class SQLTable {

	private $driver;
	private $name;
	private $fields;
	private $constraints;

	public function __construct($driver, $name) {
		$this->driver = $driver;
		$this->name = $name;
		$this->plural_name = is_null($plural) ? Util::pluralize($name) : $plural;
		$this->fields = array();
	}

	public function field($name, $type) {
		$field = new SQLField($name, $type);
		if($field->is_primary_key()) $this->primary_key = $field;
		else $this->fields[] = $field;
		return $this;
	}

	public function constraint($constraint) {

	}

	public function create_statement() {
		return 'CREATE TABLE ' . $driver->escape($this->plural_name) . 
	}
}

?>
