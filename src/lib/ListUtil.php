<?php

/* A set of utilties for dealing with sequential PHP arrays. */

class ListUtil {

	/* Return whether a value is a sequential array which may be used with
	 * the functions in this module. */
	public static function is_list($x) {
		if(!is_array($x)) return false;
		$i = 0;
		foreach($x as $k => $v) {
			if($k !== $i) return false;
			++$i;
		}
		return true;
	}

	/* Return a list which begins with all of the elements of `$list1`
	 * followed by any part of `$list2` which makes `$list2` longer than
	 * `$list1`, if it is longer. */
	public static function overlaid($list1, $list2) {
		return $list1 + $list2;
	}

	/* If `$list2` is longer than `$list1`, append the elements which
	 * make it longer to `$list1`. Return a copy of the modified
	 * `$list1`. */
	public static function overlay(&$list1, $list2) {
		return $list1 += $list2;
	}

	/* Return whether two lists have the same contents according to non-
	 * strict (==) comparison. */
	public static function loosely_equal($list1, $list2) {
		return $list1 == $list2;
	}

	/* Return whether two lists have the same contents according to
	 * strict (===) comparison. */
	public static function equal($list1, $list2) {
		return $list1 === $list2;
	}

	/* Split an array into sub-arrays of size `$n`, where the last chunk
	 * may contain fewer than `$n` elements. */
	public static function chunks($array, $n) {
		return array_chunk($array, $n);
	}

	/* Get the `$j`th column of a 2-dimensional array as its own array. */
	public static function column($array, $j) {
		return array_column($array, $j);
	}

	/* Get an associative array mapping the values of the given array to
	 * the number of times they occur. */
	public static function histogram($array) {
		return array_count_values($array);
	}




	/* Return a list of all values which are in `$list1` but not in
	 * `$list2`, `$list3`, etc. */
	public static function difference(/* $list1, $list2, ... */) {
		return call_user_func_array('array_diff', func_get_args());
	}

	/* Return an array filled with `$n` elements of the given value.
	 * PHP 4.2.0+ */
	public static function fill($n, $value) {
		return array_fill(0, $n, $value);
	}

	/* Return an array filtered by the given predicate.
	 * PHP 4.0.6+ */
	public static function filter($array, $callback) {
		return array_filter($array, $callback);
	}

	/* Exchange the values of an array with their indices.
	 * PHP 4+ */
	public static function invert($array) {
		return array_flip($array);
	}

	/* Return all of the elements which are in all of the given arrays.
	 * Note that indices are not readjusted.
	 * PHP 4.0.4+ */
	public static function intersection($array) {
		$args = func_get_args();
		return call_user_func_array('array_intersect', $args);
	}

	/* Apply a callback to all of the elements in an array and return
	 * the results in an array.
	 * PHP 4.0.6+ */
	public static function map($array, $callback) {
		return array_map($callback, $array);
	}

	/* Apply a callback which accepts `n` arguments to `n` arrays. Return
	 * the results in an array.
	 * PHP 4.0.6+ */
	public static function map_n($arrays, $callback) {
		array_unshift($arrays, $callback);
		return call_user_func('array_map', $arrays);
	}

	/* Concatenate multiple arrays into one. */
	public static function concatenate(/* $array1, ... */) {
		$args = func_get_args();
		return call_user_func('array_merge', $args);
	}
}

?>
