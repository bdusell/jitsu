<?php

class ArrayUtil {

	/* Walk an array recursively, applying a callback in depth-first search
	 * order. Optionally provide a user data argument to be passed to the
	 * callback each time it is called.
	 *
	 * The signature of the callback is expected to be:
	 * `function(&$value, $key [, $&userdata])` */
	public static function dfs(&$array, $callback, $userdata = null) {
		array_walk_recursive($array, $callback, $userdata);
	}

	/* Return the number of leaf elements in a recursive array
	 * structure. */
	public static function num_leaves($array) {
		return count($array, COUNT_RECURSIVE);
	}
}

?>
