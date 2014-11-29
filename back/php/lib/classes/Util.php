<?php

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

	/* Get a value from an array, or a default value if the key is not
	 * present. */
	public static function get($array, $key, $default = null) {
		if(array_key_exists($key, $array)) {
			return $array[$key];
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
