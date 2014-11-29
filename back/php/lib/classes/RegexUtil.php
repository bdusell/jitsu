<?php

class RegexUtil {

	/* Create a regular expression from a PCRE pattern for use with this
	 * module. Do not include the delimiters; they will be added and
	 * escaped automatically. Specify the flags in a separate string. If
	 * you want to avoid the overhead of escaping the delimiters, you may
	 * optionally provide the start and ending delimiters to use. The
	 * ending delimiter defaults to the start delimiter if null. */
	public static function create($pat, $flags = '', $start = null, $end = null) {
		if($start === null) {
			return '/' . str_replace($pat, '/', '\\/') . '/' . $flags;
		} else {
			if($end === null) $end = $start;
			return $start . $pat . $end . $flags;
		}
	}

	/* Get an error string for a `preg` error code. */
	public static function error_string($code) {
		static $codes = array(
			PREG_NO_ERROR => 'no error',
			PREG_INTERNAL_ERROR => 'internal error',
			PREG_BACKTRACK_LIMIT_ERROR => 'backtrack limit error',
			PREG_RECURSION_LIMIT_ERROR => 'recursion limit error',
			PREG_BAD_UTF8_ERROR => 'bad utf-8 error',
			PREG_BAD_UTF8_OFFSET_ERROR => 'bad utf-8 offset error'
		);
		return $codes[$code];
	}

	private static function _throw_error($code = null) {
		if($code === null) $code = preg_last_error();
		throw new RuntimeException(
			'regular expression error: ' . self::error_string($code),
			$code
		);
	}

	private static function _check_error() {
		$code = preg_last_error();
		if($code != PREG_NO_ERROR) {
			self::_throw_error($code);
		}
	}

	private static function _match($regex, $str, $flags, $offset) {
		$r = preg_match($regex, $str, $matches, $flags, $offset);
		if($r === false) {
			self::_throw_error();
		} elseif($r) {
			return $matches;
		} else {
			return null;
		}
	}

	/* Test a regular expression against a string and return a
	 * `RegexUtilMatch` object, or null if there was no match. Optionally
	 * provide a starting string offset. Throws `RuntimeException` if the
	 * regular expression is not valid. The match at index 0 is the part
	 * of the string which matched the whole pattern. */
	public static function match($regex, $str, $offset = 0) {
		$r = self::_match($regex, $str, 0, $offset);
		if($r !== null) $r = new RegexUtilMatch($r);
		return $r;
	}

	/* Same behavior as `match`, except that if the string matches the
	 * regular expression, the return value includes the starting indices
	 * of the matches as well. */
	public static function match_with_offsets($regex, $str, $offset = 0) {
		$r = self::_match($regex, $str, PREG_OFFSET_CAPTURE, $offset);
		if($r !== null) {
			$r = new RegexUtilMatch(
				array_column($r, 0),
				array_column($r, 1)
			);
		}
		return $r;
	}

	public static function match_all($regex, $str, $offset = 0) {
		$r = preg_match_all($regex, $str, $matches, PREG_SET_ORDER, $offset);
		if($r === false) {
			self::_throw_error();
		}
		$match_objs = array();
		foreach($matches as $match_set) {
			$match_objs[] = new RegexUtilMatch($match_set);
		}
		return $match_objs;
	}

	public static function match_all_with_offsets($regex, $str, $offset = 0) {
		$r = preg_match_all(
			$regex, $str, $matches,
			PREG_SET_ORDER | PREG_OFFSET_CAPTURE,
			$offset
		);
		if($r === false) {
			self::_throw_error();
		}
		$match_objs = array();
		foreach($matches as $match_set) {
			$match_objs[] = new RegexUtilMatch(
				array_column($match_set, 0),
				array_column($match_set, 1)
			);
		}
		return $match_objs;
	}

	/* Escape a string for use in a regular expression (only use the
	 * generated pattern with this module). If used with a pattern where
	 * the delimiter is being explicitly set, provide that delimiter as
	 * the second argument. */
	public static function escape($str, $delim = null) {
		return preg_quote($str, $delim);
	}

	/* Replace the portion of a string which matches a regular expression
	 * with another string. The replacement string may use backreferences
	 * in the form `\n`, `$n`, or `${n}`. Optionally specify a limit for
	 * the number of replacements which may be made (pass `null` for
	 * unlimited). Stores the number of replacements made in the optional
	 * `$count` variable.
	 *
	 * If `$replacement` is not a string or array, it will be interpreted
	 * as a callback with the signature `function($match)` whose return
	 * value will be used to generate the replacement strings.
	 *
	 * Any one of the arguments `$regex`, `$replacement`, or `$str` may
	 * be an array of multiple values. Whenever each is a scalar, it
	 * applies to all the values in the other arguments, be they scalars or
	 * arrays. Whenever each is an array, it applies pairwise to the other
	 * array arguments.
	 *
	 * When `$regex` is an array tested against a scalar `$str`, all of the
	 * patterns are tested as a logical "or".
	 *
	 * When `$replacement` is an array with too few elements for the other
	 * array arguments, the missing values are assumed to be the empty
	 * string. If it is an array, it may contain only strings, not
	 * callbacks.
	 *
	 * When `$str` is an array, an array of the replaced strings is
	 * returned.
	 * */
	public static function replace(
		$regex, $replacement, $str,
		$limit = null, &$count = null)
	{
		if(!is_string($replacement) && !is_array($replacement)) {
			return self::replace_with_callback(
				$regex, $replacement, $str,
				$limit, $count
			);
		}
		$r = preg_replace(
			$regex, $replacement, $str,
			$limit === null ? -1 : $limit, $count
		);
		if($r === null) {
			self::_throw_error();
		}
		return $r;
	}

	/* Like `replace`, except that the second parameter is always
	 * interpreted as a callback, allowing function names, etc. to be
	 * passed. */
	public static function replace_with(
		$regex, $callback, $str,
		$limit = null, &$count = null)
	{
		$r = preg_replace_callback(
			$regex, $callback, $str,
			$limit === null ? -1 : $limit, $count
		);
		if($r === null) {
			self::_throw_error();
		}
		return $r;
	}

	/* Same behavior as `replace`, except that when `$strs` is an array,
	 * only strings which had a replacement performed are returned in the
	 * resulting array. */
	public static function replace_filter(
		$regexes, $replacements, $strs,
		$limit = null, &$count = null)
	{
		$r = preg_filter($regexes, $replacements, $strs,
			$limit === null ? -1 : $limit, $count);
		self::_check_error();
		return $r;
	}

	/* Test a regular expression against an array of strings and return
	 * those strings which match (the result is not reindexed). */
	public static function grep($regex, $strs) {
		$r = preg_grep($regex, $strs);
		self::_check_error();
		return $r;
	}

	/* Same as `grep`, except that all of the strings which do *not* match
	 * the regular expression are returned. */
	public static function inverted_grep($regex, $strs) {
		$r = preg_grep($regex, $strs, PREG_GREP_INVERT);
		self::_check_error();
		return $r;
	}

	private static function _split($regex, $str, $limit, &$offsets, $flags) {
		if($offsets !== null) $flags |= PREG_SPLIT_OFFSET_CAPTURE;
		$r = preg_split($regex, $str, $limit, $flags);
		self::_check_error();
		if($offsets !== null) {
			$offsets = array_column($r, 1);
			$r = array_column($r, 0);
		}
		return $r;
	}

	/* Split a string by a regular expression. Optionally provide a limit
	 * to the number of splits. Store the offsets of the resulting strings
	 * in the optional `$offsets` variable. */
	public static function split($regex, $str, $limit = null, &$offsets = null) {
		return self::_split($regex, $str, $limit, $offsets, 0);
	}

	/* Like `split`, but filter out empty strings from the result. */
	public static function filter_split($regex, $str, $limit = null, &$offsets = null) {
		return self::_split($regex, $str, $limit, $offsets, PREG_SPLIT_NO_EMPTY);
	}

	/* Like `split`, except include group 1 of the splitting pattern in the
	 * results as well. */
	public static function inclusive_split($regex, $str, $limit = null, &$offsets = null) {
		return self::_split($regex, $str, $limit, $offsets, PREG_SPLIT_DELIM_CAPTURE);
	}
}

?>
