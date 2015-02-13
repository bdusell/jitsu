<?php

namespace phrame\sql\ast;

/* Abstract syntax tree node base class. */
abstract class Node {

	/* Use an array of values to set members. */
	public function __construct($attrs) {
		foreach($attrs as $name => $value) {
			$this->$name = $value;
		}
	}

	/* Accept a visitor. */
	public function accept($v) {
		return call_user_func(array($v, 'visit' . self::my_name()), $this);
	}

	private static function my_name() {
		$class = get_called_class();
		$end = strrchr($class, '\\');
		return $end === false ? $class : substr($end, 1);
	}

	private function full_class_name($name) {
		return __NAMESPACE__ . '\\' . $name;
	}

	protected function validate_class($class, $prop) {
		$value = $this->$prop;
		$full_class = $this->full_class_name($class);
		if(!($value instanceof $full_class)) {
			$this->error($prop, 'must be of type ' . $full_class);
		}
	}

	protected function validate_optional_class($class, $prop) {
		if($this->$prop !== null) {
			$this->validate_class($class, $prop);
		}
	}

	protected function validate_array($class, $prop) {
		$this->validate_emptyable_array($class, $prop);
		if(count($this->$prop) === 0) {
			$this->error($prop, 'must not be an empty array');
		}
	}

	protected function validate_emptyable_array($class, $prop) {
		$value = $this->$prop;
		$full_class = $this->full_class_name($class);
		if(!is_array($value)) {
			$this->error($prop, 'must be an array of ' . $full_class);
		}
		foreach($value as $subvalue) {
			if(!($subvalue instanceof $full_class)) {
				$this->error($prop, 'must be an array of ' . $full_class);
			}
		}
	}

	protected function validate_optional_array($class, $prop) {
		if($this->$prop !== null) {
			$this->validate_array($class, $prop);
		}
	}

	protected function validate_array_array($class, $prop) {
		$value = $this->$prop;
		$full_class = $this->full_class_name($class);
		if(!is_array($value)) {
			$this->error($prop, 'must be an array or arrays of ' . $full_class);
		}
		foreach($value as $subvalue) {
			if(!is_array($subvalue)) {
				$this->error($prop, 'must be an array or arrays of ' . $full_class);
			}
			foreach($subvalue as $subsubvalue) {
				if(!($subsubvalue instanceof $full_class)) {
					$this->error($prop, 'must be an array of arrays of ' . $full_class);
				}
			}
		}
	}

	protected function validate_const($prop) {
		$this->validate_string($prop);
	}

	protected function validate_string($prop) {
		if(!is_string($this->$prop)) {
			$this->error($prop, 'must be a string');
		}
	}

	protected function validate_bool($prop) {
		if(!is_bool($this->$prop)) {
			$this->error($prop, 'must be a boolean');
		}
	}

	protected function validate_int($prop) {
		if(!is_int($this->$prop)) {
			$this->error($prop, 'must be an integer');
		}
	}

	protected function validate_float($prop) {
		if(!is_float($this->$prop)) {
			$this->error($prop, 'must be a float');
		}
	}

	private function error($prop, $msg) {
		throw new \InvalidArgumentException(
			get_class($this) . '->' . $prop . ' ' . $msg
		);
	}
}

?>
