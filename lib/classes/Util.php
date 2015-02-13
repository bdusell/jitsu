<?php

namespace phrame;

/* General-purpose utility functions. */
class Util {

	/* Return the string represenation of a value (uses PHP `print_r`). */
	public static function str($var) {
		return print_r($var, true);
	}

	/* Return a string representation of a value as PHP code (uses PHP
	 * `var_export`). */
	public static function repr($var) {
		return var_export($var, true);
	}

	/* Print the arguments to stdout as PHP code (using `var_export`),
	 * separated by spaces and terminated with a newline. Return the
	 * first argument, which is useful for printing anonymous values in
	 * expressions. */
	public static function p(/* $arg1, ... */) {
		$first = true;
		foreach(func_get_args() as $arg) {
			if(!$first) echo ' ';
			echo self::repr($arg);
			$first = false;
		}
		echo "\n";
		if(func_num_args() > 0) {
			return func_get_arg(0);
		}
	}

	/* Transclude a PHP file (using PHP `include`) within its own symbol
	 * table scope, which may be populated with an optional array. The
	 * special symbols `$filename` and `$vars` are always passed, where
	 * `$filename` is the name of the included file and `$vars` is the
	 * symbol table that was passed. */
	public static function template($filename, $vars = null) {
		if($vars) extract($vars);
		include $filename;
	}

	/* Tell whether an array or object has a certain key or property, even
	 * if its value is null. */
	public static function has($arr_or_obj, $key_or_name) {
		if(is_array($arr_or_obj)) {
			return self::has_key($arr_or_obj, $key_or_name);
		} else {
			return self::has_prop($arr_or_obj, $key_or_name);
		}
	}

	/* Get the value under a key in an array or a property in an object,
	 * or a default value if the key or property does not exist. */
	public static function get($arr_or_obj, $key_or_name) {
		if(is_array($arr_or_obj)) {
			return self::get_key($arr_or_obj, $key_or_name);
		} else {
			return self::get_prop($arr_or_obj, $key_or_name);
		}
	}

	/* Set the a key in an array or a property in an object to some
	 * value. */
	public static function set($arr_or_obj, $key_or_name, $value) {
		if(is_array($arr_or_obj)) {
			$arr_or_obj[$key_or_name] = $value;
		} else {
			$arr_or_obj->$key_or_name = $value;
		}
	}

	/* Get a reference to the value under a key in an array or a property
	 * in an object, setting it to a default value if it has not been
	 * set. */
	public static function &get_ref(&$arr_or_obj, $key_or_name, $default) {
		if(is_array($arr_or_obj)) {
			if(!self::has_key($arr_or_obj, $key_or_name)) {
				$arr_or_obj[$key_or_name] = $default;
			}
			return $arr_or_obj[$key_or_name];
		} else {
			if(!self::has_prop($arr_or_obj, $key_or_name)) {
				$arr_or_obj->$key_or_name = $default;
			}
			return $arr_or_obj->$key_or_name;
		}
	}

	/* Tell whether an array contains a key, even if its value is null. */
	public static function has_key($array, $key) {
		return array_key_exists($key, $array);
	}

	/* Get a value from an array, or a default value if the key is not
	 * present. */
	public static function get_key($array, $key, $default = null) {
		if(array_key_exists($key, $array)) {
			return $array[$key];
		} else {
			return $default;
		}
	}

	/* Tell whether an object has a property named `$name`. */
	public static function has_prop($obj, $name) {
		return property_exists($obj, $name);
	}

	/* Get the value of the property named `$name` in the object `$obj` or
	 * a default value if the object does not have a property by that
	 * name. */
	public static function get_prop($obj, $name, $default = null) {
		if(property_exists($obj, $name)) {
			return $obj->$name;
		} else {
			return $default;
		}
	}

	/* Normalize an index to be within the range [0, `$length`], where
	 * a negative value will be treated as an offset from `$length`.
	 * Optionally provide a value to use when `$i` is null. This function
	 * is useful for computing slice ranges. */
	public static function normalize_slice_index($i, $length, $default = null) {
		if($i === null) return $default;
		if($i < 0) return max(0, $length + $i);
		return min($length, $i);
	}

	/* Given two slice indexes and a length, compute the starting offset
	 * and length of the resulting slice. The result is returned as
	 * `array($offset, $slice_length)`. This function is useful for
	 * converting slice indexes to arguments accepted by some builtin PHP
	 * functions. */
	public static function convert_slice_indexes($i, $j, $length) {
		$i = self::normalize_slice_index($i, $length, 0);
		$j = self::normalize_slice_index($j, $length, $length);
		return array(min($i, $length - 1), max(0, $j - $i));
	}
}

?>
