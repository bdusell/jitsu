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
}

?>
