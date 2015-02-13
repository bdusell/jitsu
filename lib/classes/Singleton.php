<?php

namespace phrame;

/* A mixin for creating singleton classes. Implement `instantiate` to return
 * some object. All methods of that object become available as static methods
 * of the derived class, and the object is only instantiated when one of these
 * methods is called. */
trait Singleton {

	private static $instance = null;

	protected function instantiate() {
		throw new \BadMethodCallException('instantiate is not implemented');
	}

	public static function instance() {
		if(self::$instance === null) {
			$class = get_called_class();
			self::$instance = (new $class)->instantiate();
		}
		return self::$instance;
	}

	public static function __callStatic($name, $args) {
		return call_user_func_array(array(self::instance(), $name), $args);
	}

	final private function __construct() {}
	final private function __clone() {}
}

?>
