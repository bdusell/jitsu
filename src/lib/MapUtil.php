<?php

/* A set of utilities for dealing with associative PHP arrays. */

class MapUtil {

	/* Return whether a value is an associative array which may be used
	 * with the functions in this module. */
	public static function is_map($x) {
		return is_array($x);
	}

	/* Return a map with all the key-value pairs of `$map1` and `$map2`,
	 * where values in `$map2` overwrite those in `$map1`. Equivalent
	 * to making a copy of `$map1` and then setting all of the key-value
	 * pairs in `$map2` on it. */
	public static function extended($map1, $map2) {
		return $map2 + $map1;
	}

	/* For any keys which exist in `$map2` but not `$map1`, add those key-
	 * value pairs to `$map1`. Return a copy of the modified `$map1`. */
	public static function defaults(&$map1, $map2) {
		return $map1 += $map2;
	}

	/* Return whether two maps have the same key-value pairs according to
	 * non-strict (==) comparison. */
	public static function loosely_equal($map1, $map2) {
		return $map1 == $map2;
	}

	/* Return whether two maps have the same key-value pairs according to
	 * strict (===) comparison. */
	public static function equal($map1, $map2) {
		// Note that === won't work, since maps should be unordered
		if(self::size($map1) != self::size($map2)) return false;
		foreach($map1 as $k => $v) {
			if(!self::has($map2, $k) || $v !== $map2[$k]) return false;
		}
		return true;
	}

	/* Return a copy of a map with all keys converted to upper case. */
	public static function with_upper_keys($map) {
		// PHP 4.2.0
		return array_change_key_case($map, CASE_UPPER);
	}

	/* Return a copy of a map with all keys converted to lower case. */
	public static function with_lower_keys($map) {
		// PHP 4.2.0
		return array_change_key_case($map);
	}

	/* Given a list of maps, return a list of the values stored under a
	 * certain key. Ignore any maps which do not contain this key. */
	public static function pluck($maps, $key) {
		return array_column($maps, $key);
	}

	/* Given a list of 2-element lists, generate a map whose keys are
	 * the elements in the first column and whose values are the
	 * elements in the second column. */
	public static function from_pairs($pairs) {
		return array_column($pairs, 1, 0);
	}

	/* Given a list of maps and a key, return a map mapping the values
	 * under this key to their corresponding maps. Later maps take
	 * precedence over earlier ones. If any of the maps does not contain
	 * this key, the result is undefined. */
	public static function index($maps, $key) {
		return array_column($maps, NULL, $key);
	}

	/* Generate a map from a list of keys and a list of corresponding
	 * values. It is an error for the lists to be of different lengths. */
	public static function zip($keys, $values) {
		return array_combine($keys, $values);
	}

	/* Return a map containing all key-value pairs which are in `$map1` but
	 * not in `$map2`, `$map3`, etc. Comparison is non-strict. */
	public static function loose_difference(/* $map1, $map2, ... */) {
		return call_user_func_array('array_diff_assoc', func_get_args());
	}

	/* Return a map containing all key-value pairs in `$map1` whose keys
	 * do not appear as keys in the maps `$map2`, `$map3`, etc. Comparison
	 * is non-strict. */
	public static function loose_key_difference(/* $map1, $map2, ... */) {
		return call_user_func_array('array_diff_key', func_get_args());
	}

	/* Return a map containing all key-value pairs in `$map1` which are not
	 * in `$map2`, etc. Comparison is strict. */
	public static function difference(/* $map1, $map2, ... */) {
		$args = func_get_args();
		$args[] = function($a, $b) { return $a === $b; };
		return call_user_func_array('array_diff_uassoc', $args);
	}

	/* Return a map containing all key-value pairs in `$map1` whose keys
	 * do not appear as keys in the maps `$map2`, etc. Comparison is
	 * strict. */
	public static function key_difference(/* $map1, $map2, ... */) {
		$args = func_get_args();
		$args[] = array('FuncUtil', 'key_cmp');
		return call_user_func_array('array_diff_ukey', $args);
	}
}

?>
