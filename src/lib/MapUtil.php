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
		// Note that array_merge would not be the same, since it does
		// nonsensical things with integer keys.
		// On the other hand, array_replace($map1, $map2) would be
		// equivalent.
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
			if(!self::has_key($map2, $k) || $v !== $map2[$k]) return false;
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
	 * do not appear as keys in the maps `$map2`, `$map3`, etc. */
	public static function key_difference(/* $map1, $map2, ... */) {
		return call_user_func_array('array_diff_key', func_get_args());
	}

	/* Return a map mapping all of the elements of `$keys` to copies of
	 * `$value`. */
	public static function from_keys($keys, $value) {
		return array_fill_keys($keys, $value);
	}

	/* Return a map where the keys of `$map` have been exchanged with their
	 * values. Since the map's keys are unordered, the choice in case of
	 * diplicates is undefined. */
	public static function invert($map) {
		return array_flip($map);
	}

	/* Return a map consisting of key-value pairs which appear in all of
	 * the given maps. Comparison is non-strict. One list of maps or
	 * multiple map arguments may be given. */
	public static function loose_intersection(/* $maps | $map1, ... */) {
		$args = func_get_args();
		if(func_num_args() == 1) $args = $args[0];
		return call_user_func('array_intersect_assoc', $args);
	}

	/* Return a map consisting of all key-value pairs in `$map1` whose
	 * keys appear as keys in all of `$map2`, etc. */
	public static function key_intersection(/* $map1, $map2, ... */) {
		return call_user_func('array_intersect_key', func_get_args());
	}

	/* Return whether a map contains a certain key. */
	public static function contains($map, $key) {
		return array_key_exists($key, $map);
	}

	/* Return a list of the keys in the map. */
	public static function keys($map) {
		return array_keys($map);
	}

	/* Return a list of the keys in a map which contain a certain value.
	 * Comparison is non-strict. */
	public static function loose_keys_of($map, $value) {
		return array_keys($map, $value);
	}

	/* Return a list of the keys in a map which contain a certain value.
	 * Comparison is strict. */
	public static function keys_of($map, $value) {
		return array_keys($map, $value, true);
	}

	/* Merge a series of maps recursively. Merging means that for
	 * maps with conflicting keys, the contents under these keys are
	 * merged using the procedure described shortly. Recursively means
	 * that when any merged contents are also maps, the same merging
	 * procedure is applied on them recursively.
	 * 
	 * When merged contents are both non-arrays, they are put into a list
	 * of length 2.
	 *
	 * When merged contents are both lists, they are concatenated.
	 *
	 * When merged contents are both maps, they are merged recursively.
	 *
	 * When merged contents are a list and a non-array, the elements of
	 * the list and the non-array are concatenated into one list.
	 *
	 * When merged contents are a map with no integer keys and a non-array,
	 * the non-array is placed in the map under key 0. If the map has
	 * integer keys, then the non-array is put under the key one after the
	 * largest integer in the map or at 0, whichever is greater.
	 *
	 * When merged contents are a list and a map with no integer keys,
	 * they are combined into a map which now includes key-value pairs
	 * corresponding to the indices and elements of the list. If the map
	 * has integer keys and the list is the second operand, then the keys
	 * contributed by the list begin counting up one after the largest
	 * integer key in the map or at 0, whichever is greater.
	 * */
	public static function recursively_merge(/* $map1, ... */) {
		return call_user_func('array_merge_recursive', func_get_args());
	}

	/* Merge a series of maps. This is the same as extending them, except
	 * that integer keys are re-indexed so that they never overwrite each
	 * other but count up non-decreasingly. */
	public static function merge(/* $map1, ... */) {
		return call_user_func('array_merge', func_get_args());
	}

	/* Recursively extend a series of maps in order. That is, return
	 * the same result as `extended`, except that for conflicting keys
	 * which both have arrays, the arrays are extended recursively. */
	public static function recursively_extended(/* $map1, ... */) {
		return call_user_func('array_replace_recursive', func_get_args());
	}

	/* Return the key under which a value is stored. Comparison is
	 * non-strict. */
	public static function loose_key_of($map, $value) {
		return array_search($map, $value);
	}

	/* Return the key under which a value is stored. Comparison is
	 * strict. */
	public static function key_of($map, $value) {
		return array_search($map, $value, true);
	}

	/* Return a copy of a map with no duplicate values, where the key
	 * selected for each value is determined by sort order. Comparison is
	 * strict. */
	public static function with_unique_values($map) {
		return array_unique($map, SORT_REGULAR);
	}

	/* Get a list of the values in a map. */
	public static function values($map) {
		return array_values($map);
	}
}

?>
