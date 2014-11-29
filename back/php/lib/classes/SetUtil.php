<?php

/* A set of utilities for dealing with *sets*. A *set* is defined as a PHP
 * array whose values and ordering are ignored; only the keys it contains
 * are significant, and they are treated as the elements of the set. Note that
 * this restricts the set elements to integers and non-integer strings. */
class SetUtil {

	/* Return the union of two *sets* as a *map*. Where values are defined,
	 * those in the second *set* take precedence. */
	public static function union($set1, $set2) {
		// array_replace($set1, $set2) would also work
		return $set2 + $set1;
	}

	/* Return a copy of a *set* as an *ordered map* where all keys have
	 * been converted to upper case. Where values and ordering are defined,
	 * they are preserved. */
	public static function upper($set) {
		return array_change_key_case($map, CASE_UPPER);
	}

	/* Like `upper`, but lower case. */
	public static function lower($set) {
		return array_change_key_case($map);
	}

	//--------------------------------

	/* Return a *Set* containing all the elements in `$set1` but not in
	 * `$set2`. Optionally provide a custom comparison function. */
	public static function difference($set1, $set2, $callback = null) {
		if($callback === null) {
			return array_diff_key($set1, $set2);
		} else {
			return array_diff_ukey($set1, $set2, $callback);
		}
	}

	/* Convert a list of values to a set of values. */
	public static function from_list($list) {
		return array_fill_keys($list, true);
	}

	/* Create a set of the integers `$start` through `$end` - 1. */
	public static function range($start, $end) {
		return array_fill($start, $end - $start, true);
	}

	/* Return whether two sets have the same contents. Note that there is
	 * no distinction between comparing unordered sets loosely and
	 * strictly due to the normalization of array keys in PHP. */
	public static function equal($set1, $set2) {
		// TODO
	}

	/* Return the set of elements which are in all of the given sets.
	 * Either a single list of sets or multiple set arguments may be
	 * passed. */
	public static function intersection(/* $sets | $set1, $set2, ... */) {
		$args = func_get_args();
		if(func_num_args() == 1) $args = $args[0];
		return call_user_func('array_intersect_key', $args);
	}

	/* Return whether a set contains a certain element. */
	public static function contains($set, $value) {
		return array_key_exists($value, $set);
	}

	/* Convert a set to a list of elements. */
	public static function to_list($set) {
		return array_keys($set);
	}

	/* Return the number of elements in a set. */
	public static function size($set) {
		return count($set);
	}
}

?>
