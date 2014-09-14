<?php

/* A set of utilities for dealing with PHP arrays whose *keys* are used as
 * set elements and whose values are the value `true`. Note that this limits
 * the elements of these sets to strings and integers. */

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
		return $set1 + $set2;
	}

	/* Return whether two sets have the same contents according to non-
	 * strict (==) comparison. */
	public static function loosely_equal($set1, $set2) {
		return $set1 == $set2;
	}

	/* Return whether two sets have the same contents according to strict
	 * (===) comparison. */
	public static function equal($set1, $set2) {
		// Note that === won't work, since sets should be unordered but
		// === will return false if elements were added in a different
		// order
		if(self::size($set1) != self::size($set2)) return false;
		foreach($set1 as $k => $v) {
			if(!self::has($set2, $k)) return false;
		}
		return true;
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
}

?>
