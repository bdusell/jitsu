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
	 * `$list2`, `$list3`, etc. Comparison is non-strict. */
	public static function loose_difference(/* $list1, $list2, ... */) {
		return call_user_func_array('array_diff', func_get_args());
	}

	/* Return a list consisting of `$n` copies of `$value`. */
	public static function repeat($value, $n) {
		return array_fill(0, $n, $value);
	}

	/* Return an array filtered by the given predicate. */
	public static function filter($array, $callback) {
		return array_filter($array, $callback);
	}

	/* Given a list of integers and strings, return a map mapping the
	 * values of `$list` to their indices, where later entries take
	 * precedence if there are duplicates. */
	public static function invert($list) {
		return array_flip($list);
	}

	/* Return all of the elements which are in all of the given lists.
	 * Note that indices are not readjusted. */
	public static function loose_intersection($lists) {
		return call_user_func_array('array_intersect', $lists);
	}

	/* Return a list of all indices in the given list which contain a
	 * certain value. Comparison is non-strict. */
	public static function loose_indices_of($list, $value) {
		return array_keys($list, $value);
	}

	/* Return a list of all indices in the given list which contain
	 * a certain value. Comparison is strict. */
	public static function indices_of($list, $value) {
		return array_keys($list, $value, true);
	}

	/* Apply a callback to all of the elements in a list and return
	 * the results in a list. */
	public static function map($list, $callback) {
		return array_map($callback, $list);
	}

	/* Concatenate two lists. */
	public static function concatenate($list1, $list2) {
		return array_merge($list1, $list2);
	}

	/* Pad a list to size `$n` with copies of `$value`. */
	public static function pad($list, $n, $value) {
		return array_pad($list, $n, $value);
	}

	/* Pop the last element from a list. Return the popped element or null
	 * if the list was empty. */
	public static function pop(&$list) {
		return array_pop($list);
	}

	/* Multiply all of the values in a list together. */
	public static function product($list) {
		return array_product($list);
	}

	/* Push an element onto the end of a list. Equivalent to
	 * `$list[] = $value`, which should be preferred instead. */
	public static function push(&$list, $value) {
		return array_push($list, $value);
	}

	/* Return a random element from a list. */
	public static function random($list) {
		return array_rand($list);
	}

	/* Return a list of `$n` random elements from a list. */
	public static function random_n($list, $n) {
		$result = array_rand($list, $n);
		if($n == 1) $result = array($result);
		return $result;
	}

	/* Reduce a list of a values to a single one using a binary
	 * callback. Optionally pass a non-null third argument as an initial
	 * value. If the initial value is omitted, null will be returned for
	 * an empty list, and for a list of length 1 the element inside it. */
	public static function reduce($list, $callback, $initial = null) {
		return array_reduce($list, $callback, $initial);
	}

	/* Return a the given list, reversed. */
	public static function reversed($list) {
		return array_reverse($list);
	}

	/* Search a list for a value and return the index where it was found,
	 * or else -1. Comparison is non-strict. */
	public static function loose_index_of($list, $value) {
		$result = array_search($list, $value);
		if($result === false) return -1;
		return $result;
	}

	/* Search a list for a value and return the index where it was found,
	 * or else -1. Comparison is strict. */
	public static function index_of($list, $value) {
		$result = array_search($list, $value, true);
		if($result === false) return -1;
		return $result;
	}

	/* Shift an element off the beginning of a list. Return the shifted
	 * element or null if the list was empty. */
	public static function shift(&$list) {
		return array_shift($list);
	}

	private static function _slice_j($i, $j) {
		if($j === null) {
			return null;
		} else {
			if($j < 0) {
				return $j;
			} else {
				if($j > $i) {
					return $j - $i;
				} else {
					return 0;
				}
			}
		}
	}

	/* Return a slice of a list, where `$i` is the starting index and `$j`
	 * is one past the last index, or null if all the rest of the list
	 * should be used. If `$j` is negative, this denotes the number of
	 * elements from the end of the array where the slice stops. */
	public static function slice($list, $i, $j = null) {
		return array_slice($list, $i, self::_slice_j($i, $j));
	}

	/* Replace a slice of a list with the contents of another list. Use
	 * `null` for `$j` to assign until the end of the list. Returns the
	 * replaced slice. */
	public static function assign_slice(&$list, $i, $j, $sub) {
		return array_splice($list, $i,
			$j === null ? count($list) : self::_slice_j($i, $j),
			$sub);
	}

	/* Sum all of the elements in a list together. */
	public static function sum($list) {
		return array_sum($list);
	}

	/* Unshift an element onto the beginning of a list. */
	public static function unshift(&$list, $value) {
		array_unshift($list, $value);
	}

	/* Return the length of a list. */
	public static function length($list) {
		return count($list);
	}

	/* Return whether a list contains a certain value. Comparison is non-
	 * strict. */
	public static function loose_contains($list, $value) {
		return in_array($value, $list);
	}

	/* Return whether a list contains a certain value. Comparison is
	 * strict. */
	public static function contains($list, $value) {
		return in_array($value, $list, true);
	}
}

?>
