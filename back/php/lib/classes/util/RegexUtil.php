<?php

class RegexUtil {

	public static function create($pat, $flags = '') {
		return "/$pat/$flags";
	}

	private static function _match($regex, $str, $flags, $offset) {
		$r = preg_match($regex, $str, $matches, $flags, $offset);
		if($r === false) {
			throw RuntimeException('invalid regular expression');
		} elseif($r) {
			return $matches;
		} else {
			return null;
		}
	}

	/* Test a regular expression against a string and return a list of
	 * matching groups, or null if there was no match. Optionally provide
	 * a starting string offset. Throws `RuntimeException` if the regular
	 * expression is not valid. The match at index `0` is the part of the
	 * string which matched the whole pattern. */
	public static function match($regex, $str, $offset = 0) {
		return self::_match($regex, $str, 0, $offset);
	}

	/* Save behavior as `match`, except that if the string matches the
	 * regular expression, the return value is a list of pairs of the
	 * form `array($match, $offset)`. */
	public static function match_with_offsets($regex, $str, $offset = 0) {
		return self::_match($regex, $str, PREG_OFFSET_CAPTURE, $offset);
	}

	/* Escape a string for use in a regular expression (only guaranteed for
	 * use with this module). */
	public static function escape($str) {
		return preg_quote($str, '/');
	}

	public static function replace($regex, $replacement, $str, $limit = -1) {

	}
}

?>
