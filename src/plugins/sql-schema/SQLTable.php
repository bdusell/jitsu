<?php

/* SQL table class. */

class SQLTable {

	private $driver;
	private $name;
	private $pluralized_name;
	private $fields;

	public function __construct($driver, $name, $plural = null) {
		$this->driver = $driver;
		$this->pluralized_name = is_null($plural) ? Util::pluralize($name) : $plural;
		$this->fields = array();
	}

	public function field($name, $type) {
		$field = new SQLField($name, $type);
		if($field->is_primary_key()) $this->primary_key = $field;
		else $this->fields[] = $field;
		return $this;
	}

	public function create_statement() {
		return 'CREATE TABLE ' . $this->escape($this->name);
	}
}

?>
