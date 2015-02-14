<?php

namespace phrame;

/* Utilities for dealing with integers. */
class IntUtil {

	/* Compare two integers. Return a negative value if `$a` is less than
	 * `$b`, 0 if `$a` is equal to `$b`, and a number greater than 0 if
	 * `$a` is greater than `$b`. */
	public static function cmp($a, $b) {
		return $a < $b ? -1 : $a == $b ? 0 : 1;
	}

	/* Convert a binary string to an integer, or a real number if the value
	 * is too large. */
	public static function from_binary($s) {
		return bindec($s);
	}
}

?>
