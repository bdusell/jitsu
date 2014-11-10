<?php

class ArrayUtil {

	/* Walk an array recursively, applying a callback in depth-first search
	 * order. Optionally provide a user data argument to be passed to the
	 * callback each time it is called.
	 *
	 * The signature of the callback is expected to be:
	 * `function(&$value, $key [, $&userdata])` */
	public static function apply_depth_first(&$array, $callback, $userdata = null) {
		array_walk_recursive($array, $callback, $userdata);
	}

	/* Return the number of leaf elements in a recursive array
	 * structure. */
	public static function count_leaves($array) {
		return count($array, COUNT_RECURSIVE);
	}

	public static function at($array, $i) {
		foreach(array_slice($array, $i, 1, true) as $k => $v) {
			return array($k, $v);
		}
		throw new OutOfBoundsException('no key-value pair exists at position ' . $i);
	}
}

?>
