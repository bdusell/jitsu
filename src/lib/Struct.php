<?php

/* "Blesses" an associative array. According to the PHP documentation on the
comparison of objects, it should be possible to compare Struct objects or
arrays of Struct objects for equality just like any other associative array. */
class Struct {

	private $attrs;

	public function __construct($attrs) {
		$this->attrs = $attrs;
	}

	public function __get($name) {
		return $this->attrs[$name];
	}

	public function __set($name, $value) {
		$this->attrs[$name] = $value;
	}
}

?>
