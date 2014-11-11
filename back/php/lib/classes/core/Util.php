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
	 * first argument, which is useful for printing anonymous values. */
	public static function p(/* *args */) {
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
	 * table scope, which may be populated with an optional array. */
	public static function template($filename, $symbols = null) {
		if($symbols) extract($symbols);
		include $filename;
	}
}

?>
