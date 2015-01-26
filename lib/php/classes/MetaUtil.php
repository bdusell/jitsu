<?php

class MetaUtil {

	public static function constant_exists($name) {
		return defined($name);
	}

	public static function function_exists($name) {
		return function_exists($name);
	}

	public static function call_function($name /* $arg1, ... */) {
		$args = func_get_args();
		array_shift($args);
		return call_user_func_array($name, $args);
	}

	public static function apply_function($name, $args) {
		return call_user_func_array($name, $args);
	}

	public static function apply_method($obj, $name, $args) {
		return call_user_func_array(array($obj, $name), $args);
	}

	public static function apply_static_method($class_name, $name, $args) {
		return call_user_func_array(array($class_name, $name), $args);
	}

	public static function apply_constructor($class_name, $args) {
		(new ReflectionClass($class_name))->newInstanceArgs($args);
	}

	/* Get the class or type of a value as a string. */
	public static function type_name($value) {
		return is_object($value) ? get_class($value) : gettype($value);
	}
}

?>
