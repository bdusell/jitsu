<?php

class ArrayUtil {

	/* Return the number of key-value pairs in an array. */
	public static function size($array) {
		return count($array);
	}

	/* Alias for `size`. */
	public static function length($array) {
		return count($array);
	}

	/* Return whether an array contains a key. */
	public static function has_key($array, $key) {
		return array_key_exists($key, $array);
	}

	/* Return all of the keys of an array as a sequential array. */
	public static function keys($array) {
		return array_keys($array);
	}

	/* Return all of the values of an array in a sequential array. */
	public static function values($array) {
		return array_values($array);
	}

	/* Append a value to the end of an array. */
	public static function append(&$array, $value) {
		$array[] = $value;
	}

	/* Append all of the values in an array to another. */
	public static function append_all(&$array1, $array2) {
		foreach($array2 as $value) {
			$array1[] = $value;
		}
	}

	/* Concatenate two sequential arrays. */
	public static function concat($array1, $array2) {
		return array_merge($array1, $array2);
	}

	/* Alias for `append`. */
	public static function push(&$array, $value) {
		$array[] = $value;
	}

	/* Pop a value of the end of an array. Return null if the array
	 * was empty. */
	public static function pop(&$array) {
		return array_pop($array);
	}

	/* Shift an element off the beginning of an array. Return null if the
	 * array was empty. */
	public static function shift(&$array) {
		return array_shift($array);
	}

	/* Prepend a value to the beginning of an array. */
	public static function unshift(&$array, $value) {
		return array_unshift($array, $value);
	}

	/* Return the key under which a value is stored in an array. Comparison
	 * is strict. Returns null if the value does not exist in the array. */
	public static function key_of($array, $value) {
		$r = array_search($value, $array, true);
		return $r === false ? null : $r;
	}

	/* Alias of `key_of`. */
	public static function index_of($array, $value) {
		return self::key_of($array, $value);
	}

	/* Return whether an array contains a certain value. Comparison is
	 * strict. */
	public static function contains($array, $value) {
		return in_array($value, $array, true);
	}

	/* Return the `$i`th value in an array according to ordering (not by
	 * key value or index). */
	public static function value_at($array, $i) {
		return array_slice($array, $i, 1)[0];
	}

	/* Return the `$i`th key-value pair in an array. */
	public static function pair_at($array, $i) {
		foreach(array_slice($array, $i, 1, true) as $k => $v) {
			return array($k, $v);
		}
	}

	/* Return the `$i`th key in an array. */
	public static function key_at($array, $i) {
		foreach(array_slice($array, $i, 1, true) as $k => $v) {
			return $k;
		}
	}

	/* Return a slice of a sequential array, where `$i` is the starting
	 * index and `$j` is one past the last index, or null if all the rest
	 * of the list should be used. If `$j` is negative, this denotes the
	 * number of elements from the end of the array where the slice stops.
	 */
	public static function slice($array, $i, $j = null) {
		return self::_slice($array, $i, $j, false);
	}

	/* Return a slice of an array as an associative array. The offsets used
	 * refer to the ordering of the key-value pairs in the input array;
	 * array keys are preserved. */
	public static function pair_slice($array, $i, $j = null) {
		return self::_slice($array, $i, $j, true);
	}

	/* Replace a slice of an array with the contents of another array. Use
	 * `null` for `$j` to assign until the end of the list. Returns the
	 * replaced slice. */
	public static function assign_slice(&$array, $i, $j, $sub) {
		list($offset, $len) = Util::convert_slice_indexes($i, $j, count($array));
		return array_splice($array, $offset, $len, $sub);
	}

	/* Remove a slice from an array. */
	public static function remove_slice(&$array, $i, $j = null) {
		return self::assign_slice($array, $i, $j, array());
	}

	/* Reverse a sequential array. */
	public static function reverse($array) {
		return array_reverse($array);
	}

	/* Reverse the ordering of key value pairs in an array. */
	public static function reverse_pairs($array) {
		return array_reverse($array, true);
	}

	/* Generate a sequential array consisting of a range of numbers, with
	 * an optional step size which defaults to integer 1. If the step size
	 * is an integer, then the range is non-inclusive (it stops 1 before
	 * `$j`). Otherwise, the range is inclusive. */
	public static function range($i, $j, $step) {
		return range($i, $j - is_int($step), $step);
	}

	/* Construct an associative array from an array of pairs, where the
	 * first element of each is the key and the second is the value. */
	public static function from_pairs($pairs) {
		return array_column($pairs, 1, 0);
	}

	/* Construct an associative array from an array of keys and an array
	 * of values. */
	public static function from_lists($keys, $values) {
		return array_combine($keys, $values);
	}

	/* Return an associative array mapping the values in an array to
	 * `true`, a structure which can be used like a set. Optionally
	 * specify a value other than `true` to use. */
	public static function to_set($array, $value = true) {
		return array_fill_keys($array, $value);
	}

	/* Return a sequential array of `$n` copies of `$value`. */
	public static function fill($value, $n) {
		return array_fill(0, $n, $value);
	}

	/* Pad a sequential array to a certain length with copies of
	 * `$value`. The sign of `$n` determines whether the array is padded
	 * at the beginning or the end. */
	public static function pad($array, $value, $n) {
		return array_pad($array, $n, $value);
	}

	/* Return a sequential array of all of the keys in an array mapping to
	 * a certain value. Strict comparison is used. */
	public static function keys_of($array, $value) {
		return array_keys($array, $value, true);
	}

	/* Get a sequential array of all the values under a certain key in a
	 * list of arrays. Wherever that key is missing from an array, that
	 * array is simply skipped. */
	public static function pluck($arrays, $key) {
		return array_column($arrays, $key);
	}

	/* Invert an associative array so that its values become the keys and
	 * vice-versa. */
	public static function invert($array) {
		return array_flip($array);
	}

	/* Overwrite values in the first map with those in the second. */
	public static function extend($array1, $array2) {
		return array_replace($array1, $array2);
	}

	/* Recursively extend two nested array structures. */
	public static function rextend($array1, $array2) {
		return array_replace_recursive($array1, $array2);
	}

	/* Split an array into chunks according to its ordering. Returns a
	 * sequential array containing the chunks. */
	public static function chunks($a, $n) {
		return array_chunk($a, $n);
	}

	/* Apply a function to the elements of an array and return the results
	 * as an array. Keys are preserved. */
	public static function map($array, $callback) {
		return array_map($callback, $array);
	}

	/* Filter the values in an associative array by a predicate of the form
	 * `function($value)`. If none is given, filter all truthy values. */
	public static function filter($array, $callback = null) {
		return array_filter($array, $callback);
	}

	/* Filter the values in an associative array by a predicate of the form
	 * `function($key, $value)`. */
	public static function filter_pairs($array, $callback) {
		return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
	}

	/* Add all of the values in an array together. */
	public static function sum($array) {
		return array_sum($array);
	}

	/* Multiply all of the values of an array together. */
	public static function product($array) {
		return array_product($array);
	}

	/* Reduce an array of values using a binary function. Optionally
	 * provide an initial value; if this is null, then it is ignored and
	 * arrays can be reduced using the result from the first callback. */
	public static function reduce($array, $callback, $initial = null) {
		return array_reduce($array, $callback, $initial);
	}

	/* Apply a callback to every element of an array. The callback should
	 * be in the form `function($value, $key)`. */
	public static function apply(&$array, $callback) {
		array_walk($array, $callback);
	}

	/* Traverse a nested array structure's leaves in order. The callback
	 * should be in the form `function($value, $key)`. */
	public static function traverse_leaves(&$array, $callback) {
		array_walk_recursive($array, $callback);
	}

	/* Return an associative array containing all key-value pairs which
	 * exist in the first array but not in the second. Optionally provide
	 * comparison functions for the keys and values. */
	public static function pair_difference($array1, $array2, $key_cmp = null, $value_cmp = null) {
		// Seriously, PHP? seriously?
		if($key_cmp === null) {
			if($value_cmp === null) {
				return array_diff_assoc($array1, $array2);
			} else {
				return array_udiff_assoc($array1, $array2, $value_cmp);
			}
		} else {
			if($value_cmp === null) {
				return array_diff_uassoc($array1, $array2, $key_cmp);
			} else {
				return array_udiff_uassoc($array1, $array2, $value_cmp, $key_cmp);
			}
		}
	}

	/* Return an associative array containing all key-value pairs in the
	 * first array whose keys do not exist in the second. Optionally
	 * provide a comparison function for the keys. */
	public static function key_difference($array1, $array2, $key_cmp = null) {
		if($key_cmp === null) {
			return array_diff_key($array1, $array2);
		} else {
			return array_diff_ukey($array1, $array2, $key_cmp);
		}
	}

	/* Return an associative array containing all key-value pairs in the
	 * first array whose values do not exist in the second. Uses string
	 * comparison. Optionally provide a comparison function. */
	public static function value_difference($array1, $array2, $value_cmp = null) {
		if($value_cmp === null) {
			return array_diff($array1, $array2);
		} else {
			return array_udiff($array1, $array2, $value_cmp);
		}
	}

	/* Return an associative array whose key-value pairs exist in both
	 * arrays. Uses string comparison for values. Optionally provide
	 * comparison functions for keys and values. */
	public static function pair_intersection($array1, $array2, $key_cmp = null, $value_cmp = null) {
		if($key_cmp === null) {
			if($value_cmp === null) {
				return array_intersect_assoc($array1, $array2);
			} else {
				return array_uintersect_assoc($array1, $array2, $value_cmp);
			}
		} else {
			if($value_cmp === null) {
				return array_intersect_uassoc($array1, $array2, $key_cmp);
			} else {
				return array_uintersect_uassoc($array1, $array2, $value_cmp, $key_cmp);
			}
		}
	}

	/* Return an associative array containing all key-value pairs in the
	 * first array whose keys exist in the second. Optionally provide a
	 * key comparison function. */ 
	public static function key_intersection($array1, $array2, $key_cmp = null) {
		if($key_cmp === null) {
			return array_intersect_key($array1, $array2);
		} else {
			return array_intersect_ukey($array1, $array2, $key_cmp);
		}
	}

	/* Return an associative array containing all key-value pairs in the
	 * first array whose values exist in the second. Uses string
	 * comparison. Optionally provide a comparison function. */
	public static function value_intersection($array1, $array2, $value_cmp = null) {
		if($value_cmp === null) {
			return array_intersect($array1, $array2);
		} else {
			return array_uintersect($array1, $array2, $value_cmp);
		}
	}

	/* Remove all key-value pairs from an array whose values are duplicates
	 * of other values earlier in the array. */
	public static function unique_values($array) {
		return array_unique($array, SORT_REGULAR);
	}

	/* Pick a random key from an array. */
	public static function random_key($array) {
		return array_rand($array);
	}

	/* Pick a random value from an array. */
	public static function random_value($array) {
		return $array[array_rand($array)];
	}

	/* Pick a random key-value pair from an array. */
	public static function random_pair($array) {
		$k = array_rand($array);
		return array($k, $array[$k]);
	}

	/* Pick `$n` random keys from an array as a sequential array. */
	public static function random_keys($array, $n) {
		$r = array_rand($array, $n);
		if($n === 1) $r = array($r);
		return $r;
	}

	/* Randomly shuffle and re-index the values of an array in-place. */
	public static function shuffle(&$array) {
		shuffle($array);
	}

	/* Sort and re-index the values of an array. Optionally provide a
	 * comparison function. */
	public static function sort(&$array, $value_cmp = null) {
		if($value_cmp === null) {
			sort($array);
		} else {
			usort($array, $value_cmp);
		}
	}

	/* Sort and re-index the values of an array in-place, in reverse. */
	public static function reverse_sort(&$array) {
		rsort($array);
	}

	/* Sort the key-value pairs of an array in-place based on their
	 * values. Optionally provide a comparison function. */
	public static function sort_pairs(&$array, $value_cmp = null) {
		if($value_cmp === null) {
			asort($array);
		} else {
			uasort($array, $value_cmp);
		}
	}

	/* Sort the key-value pairs of an array in-place based on their values,
	 * in reverse order. */
	public static function reverse_sort_pairs(&$array) {
		arsort($array);
	}

	/* Sort the key-value pairs of an array in-place based on their keys.
	 * Optionally provide a comparison function. */
	public static function sort_keys(&$array, $key_cmp = null) {
		if($key_cmp === null) {
			ksort($array);
		} else {
			uksort($array, $key_cmp);
		}
	}

	/* Sort the key-value pairs of an array in-place based on their keys,
	 * in reverse order. */
	public static function reverse_sort_keys(&$array) {
		krsort($array);
	}

	/* Sort the key-value pairs of an array of strings based on their
	 * values in a human-sensible way, in-place. */
	public static function human_sort_values(&$array) {
		natsort($array);
	}

	/* Sort the key-value pairs of an array of strings based on their
	 * values in a human-sensible way, ignoring case, in-place. */
	public static function ihuman_sort_values(&$array) {
		natcasesort($array);
	}

	/* Convert the keys in an array to lower case. */
	public static function lower_keys($a) {
		return array_change_key_case($a);
	}

	/* Convert the keys in an array to upper case. */
	public static function upper_keys($a) {
		return array_change_key_case($a, CASE_UPPER);
	}

	/* Normalize an arbitrary string or integer to its PHP array key
	 * equivalent. PHP arrays normalize their keys by converting all
	 * strings of decimal digits without superfluous leading 0's to their
	 * integer equivalents. Integers are always left alone. Some array
	 * functions do strict type checking against keys, so it may be
	 * necessary to normalize a string to ensure that an arbitrary string
	 * may be used. */
	public static function normalize_key($k) {
		if(is_int($k)) return $k;
		$k = (string) $k;
		if(ctype_digit($k) && (strlen($k) === 1 || $k[0] !== '0')) {
			return (int) $k;
		}
		return $k;
	}

	/* Count the number of times each value appears in a list and produce
	 * an associative array mapping those values to their frequencies. Of
	 * course, this limits the list items to strings and integers. */
	public static function count_values($list) {
		return array_count_values($list);
	}

	/* Traverse a nested array structure and return the number of its
	 * leaves. */
	public static function count_leaves($array) {
		return count($array, COUNT_RECURSIVE);
	}

	private static function _slice($array, $i, $j, $preserve_keys) {
		list($offset, $len) = Util::convert_slice_indexes($i, $j, count($array));
		return array_slice($array, $offset, $len, $preserve_keys);
	}
}

?>
