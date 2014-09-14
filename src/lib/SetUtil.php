<?php

/* A set of utilities for dealing with PHP arrays whose *keys* are used as
 * set elements and whose values are the value `true`. Note that this limits
 * the elements of these sets to strings and integers. Since integer-strings
 * are always converted to integers, the two cannot be distinguished, so
 * strict comparison of set elements has no meaning. */

class SetUtil {

	/* Return whether a value is a valid set-like array which may be used
	 * with the functions in this module. */
	public static function is_set($x) {
		if(!is_array($x)) return false;
		foreach($x as $v) {
			if($v !== true) return false;
		}
		return true;
	}

	/* Return the union of two sets. */
	public static function union($set1, $set2) {
		// array_replace would also work
		return $set1 + $set2;
	}

	/* Return whether two sets have the same contents. */
	public static function equal($set1, $set2) {
		return $set1 == $set2;
	}

	/* Return a copy of a set with all string elements converted to upper
	 * case. */
	public static function to_upper($set) {
		return array_change_key_case($map, CASE_UPPER);
	}

	/* Return a copy of a set with all string elements converted to lower
	 * case. */
	public static function to_lower($set) {
		return array_change_key_case($map);
	}

	/* Return a set of all elements in `$set1` which are not in `$set2`,
	 * `$set3`, etc. */
	public static function difference(/* $set1, $set2, ... */) {
		return call_user_func_array('array_diff_key', func_get_args());
	}

	/* Convert a list of values to a set of values. */
	public static function from_list($list) {
		return array_fill_keys($list, true);
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
}

?>
