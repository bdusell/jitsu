<?php

/**
 * @internal
 */

namespace jitsu\impl;

/**
 * Normalize an index to be within the range [0, `$length`], where
 * a negative value will be treated as an offset from `$length`.
 *
 * This function is useful for computing slice ranges.
 *
 * @param int $i
 * @param int $length
 * @param int|null $default An optionally value to use when `$i` is `null`.
 * @return int|null
 */
function normalize_slice_index($i, $length, $default = null) {
	if($i === null) return $default;
	if($i < 0) return max(0, $length + $i);
	return min($length, $i);
}

/**
 * Given two slice indices and a length, compute the starting offset
 * and length of the resulting slice.
 *
 * This function is useful for converting slice indexes to arguments
 * accepted by some builtin PHP functions.
 *
 * @param int $i
 * @param int $j
 * @param int $length
 * @return array A pair in the form `array($offset, $slice_length)`.
 */
function convert_slice_indexes($i, $j, $length) {
	$i = self::normalize_slice_index($i, $length, 0);
	$j = self::normalize_slice_index($j, $length, $length);
	return array(min($i, $length - 1), max(0, $j - $i));
}
