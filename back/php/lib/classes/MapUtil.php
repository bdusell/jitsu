<?php

/* A set of utilities for dealing with *Maps*. A *Map* is defined as a PHP
 * array whose ordering is ignored; only the mapping of keys to values is
 * significant. Every *Map* is a valid *Set* and *Bag*. */
class MapUtil {

	/* Return a *Map* of all the key-value pairs that are in `$map1` but
	 * not in `$map2`. Elements are compared by their string
	 * representations. Optionally provide a custom comparison function. */
	public static function difference($map1, $map2, $callback = null) {
		if($callback === null) {
			return array_diff_assoc($map1, $map2);
		} else {
			return array_diff_uassoc($map1, $map2, $callback);
		}
	}

	///-------------------------

	/* Return whether a value is an associative array which may be used
	 * with the functions in this module. */
	public static function is_map($x) {
		return is_array($x);
	}

	/* Get the value stored at `$key` in `$map`. */
	public static function get($map, $key) {
		return $map[$key];
	}

	/* Set the value stored at `$key` in `$map` to `$value`. */
	public static function set($map, $key, $value) {
		return $map[$key] = $value;
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

	/* Assign all key-value pairs in `$src` to `$dest`. */
	public static function extend(&$dest, $src) {
		foreach($src as $k => $v) {
			$dest[$k] = $v;
		}
		return $dest;
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
		return array_change_key_case($map, CASE_UPPER);
	}

	/* Return a copy of a map with all keys converted to lower case. */
	public static function with_lower_keys($map) {
		return array_change_key_case($map);
	}

	/* Given a list of maps, return a list of the values stored under a
	 * certain key. Ignore any maps which do not contain this key. */
	public static function pluck($maps, $key) {
		return array_column($maps, $key);
	}

	/* Given a map and a list of keys (as an array or as a variadic
	 * argument list), return a list of the values under those keys. */
	public static function pick(/* $array, $keys ... */) {
		$args = func_get_args();
		$array = array_shift($args);
		if(count($args) == 1 && is_array($args[0])) {
			$keys = $args[0];
		} else {
			$keys = $args;
		}
		$result = array();
		foreach($keys as $key) {
			$result[] = $array[$key];
		}
		return $result;
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
		return array_column($maps, null, $key);
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
	 * duplicates is undefined. */
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
	public static function has_key($map, $key) {
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

	/* Merge a series of maps. This is the same as extending them, except
	 * that integer keys are re-indexed so that they never overwrite each
	 * other but count up non-decreasingly. */
	public static function merge(/* $map1, ... */) {
		return call_user_func('array_merge', func_get_args());
	}

	/* Merge a series of maps recursively. Merging means that for
	 * maps with conflicting keys, the contents under these keys are
	 * merged using the logic tabulated below. Recursively means
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
	 * the list, and the non-array, are concatenated into one list.
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

	/* Recursively extend a series of maps in order. That is, return
	 * the same result as `extended`, except that for conflicting keys
	 * which both have arrays, the arrays are extended recursively. Note
	 * that this means that when two lists are in conflict, the first list
	 * is not overwritten, only overlaid, which may not be exactly what you
	 * want. */
	public static function recursively_extended(/* $map1, ... */) {
		return call_user_func('array_replace_recursive', func_get_args());
	}

	/* Return the key under which a value is stored. Comparison is
	 * non-strict. If there is more than one such key, the choice is
	 * undefined. */
	public static function loose_key_of($map, $value) {
		return array_search($map, $value);
	}

	/* Return the key under which a value is stored. Comparison is
	 * strict. If there is more than one such key, the choice is
	 * undefined. */
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

	/* Apply a callback to every value in a map. Optionally provide
	 * user data to be passed to each call.
	 *
	 * The signature of the callback is expected to be:
	 * `function(&$value, $index [, &$userdata])` */
	public static function apply(&$list, $callback, $userdata = null) {
		return array_walk($list, $callback, $userdata);
	}

	/* Return the number of key-value pairs in a map. */
	public static function size($map) {
		return count($map);
	}

	/* Return whether a value exists in a map. Comparison is non-strict. */
	public static function loose_contains_value($map, $value) {
		return in_array($value, $map);
	}

	/* Return whether a value exists in a map. Comparison is strict. */
	public static function contains_value($map, $value) {
		return in_array($value, $map, true);
	}

	/* Maps which contain the same key-value pairs will compare strictly
	 * inequal (!==) if the pairs were defined in a different order. Use
	 * this function to normalize unordered maps so that they compare
	 * strictly equal if and only if they contain the same key-value pairs,
	 * where comparison of values is strict. Modifies the map in place and
	 * returns a copy of the normalized map. */
	public static function normalize(&$map) {
		ksort($map);
		return $map;
	}

	public static function has_only_keys($array, $keys, &$unexpected = null) {
		$gather = func_num_args() > 2;
		$key_set = self::to_set($keys);
		foreach($array as $key => $value) {
			if(!isset($key_set[$key])) {
				if($gather) {
					$unexpected[] = $key;
				} else {
					return false;
				}
			}
		}
		return !$unexpected;
	}

	public static function has_keys($array, $keys, &$missing = null) {
		$gather = func_num_args() > 2;
		foreach($keys as $key) {
			if(!isset($array[$key])) {
				if($gather) {
					$missing[] = $key;
				} else {
					return false;
				}
			}
		}
		return !$missing;
	}

	public static function has_exact_keys($array, $keys, &$unexpected = null, &$missing = null) {
		$gather = func_num_args() > 2;
		$key_set = self::to_set($keys);
		foreach($array as $key => $value) {
			if(isset($key_set[$key])) {
				unset($key_set[$key]);
			} elseif($gather) {
				$unexpected[] = $key;
			} else {
				return false;
			}
		}
		if($gather) {
			$missing = array_keys($key_set);
		}
		return !$key_set && !$unexpected;
	}
}

?>
