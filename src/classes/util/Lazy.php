<?php

namespace jitsu\util;

/* A base class for a class "decorator" which lazily instantiates objects of a
 * certain class until one of their members is accessed.
 *
 * Define `const T` in the sub-class as the name of the proxied class.
 */
class Lazy {

	private $instance = null;
	private $args;

	public function __construct() {
		$this->args = func_get_args();
	}

	public function __call($name, $args) {
		$this->instantiate();
		return call_user_func_array(array($this->instance, $name), $args);
	}

	public function __get($name) {
		$this->instantiate();
		return $this->instance->$name;
	}

	public function __set($name, $value) {
		$this->instantiate();
		$this->instance->$name = $value;
	}

	public function __isset($name) {
		$this->instantiate();
		return isset($this->instance->$name);
	}

	public function __unset($name) {
		$this->instantiate();
		unset($this->instance->$name);
	}

	public function __toString() {
		$this->instantiate();
		return (string) $this->instance;
	}

	public function __clone() {
		$this->instantiate();
		return clone $this->instance;
	}

	public function __invoke() {
		$this->instantiate();
		return call_user_func_array($this->instance, func_get_args());
	}

	public function __debugInfo() {
		$this->instantiate();
		return $this->instance->__debugInfo();
	}

	public function __sleep() {
		$this->instantiate();
		return $this->instance->__sleep();
	}

	public function __wakeup() {
		$this->instantiate();
		return $this->instance->__wakeup();
	}

	public function instantiate() {
		if($this->instance === null) {
			$class = constant(get_called_class() . '::T');
			$this->instance = \jitsu\MetaUtil::apply_constructor($class, $this->args);
			$this->args = null;
		}
	}

	public function is_instantiated() {
		return $this->instance !== null;
	}
}

?>
