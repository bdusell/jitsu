<?php

namespace phrame\impl;

/* Normalize an index to be within the range [0, `$length`], where
 * a negative value will be treated as an offset from `$length`.
 * Optionally provide a value to use when `$i` is null. This function
 * is useful for computing slice ranges. */
function normalize_slice_index($i, $length, $default = null) {
	if($i === null) return $default;
	if($i < 0) return max(0, $length + $i);
	return min($length, $i);
}

/* Given two slice indexes and a length, compute the starting offset
 * and length of the resulting slice. The result is returned as
 * `array($offset, $slice_length)`. This function is useful for
 * converting slice indexes to arguments accepted by some builtin PHP
 * functions. */
function convert_slice_indexes($i, $j, $length) {
	$i = self::normalize_slice_index($i, $length, 0);
	$j = self::normalize_slice_index($j, $length, $length);
	return array(min($i, $length - 1), max(0, $j - $i));
}

?>
