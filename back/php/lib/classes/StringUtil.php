<?php

class StringUtil {

	/* Return the characters of a string as an array. */
	public static function chars($s) {
		return str_split($s);
	}

	/* Split a string into chunks of `$n` characters each. */
	public static function chunks($s, $n) {
		return str_split($s, $n);
	}

	/* Split a string by a delimiter into an array of strings. If `$limit`
	 * is null, then all possible splits are made. If `$limit` is a
	 * positive integer, at most `$limit` splits will be made from the
	 * beginning of the string, with the rest of the string occupying the
	 * last element, so that no more than `$limit` + 1 elements will be
	 * returned. If `$limit` is negative, all parts except for the last
	 * -`$limit` are returned. */
	public static function split($s, $delim, $limit = null) {
		if($limit === null) {
			return explode($s, $delim);
		} else {
			return explode($s, $delim, $limit + ($limit >= 0));
		}
	}

	/* Join array elements into a single string with a separator (default
	 * is empty string). */
	public static function join(/* $sep = '', $strs */) {
		return call_user_func_array(
			'implode',
			func_get_args()
		);
	}

	/* Strip whitespace and null bytes from the end of a string. Optionally
	 * provide a string listing the characters to strip instead. */
	public static function rtrim(/* $s, $chars */) {
		return call_user_func_array(
			'rtrim',
			func_get_args()
		);
	}

	/* Strip whitespace and null bytes from the beginning of a string.
	 * Optionally provide a string listing the characters to strip instead.
	 */
	public static function ltrim(/* $s, $chars */) {
		return call_user_func_array(
			'ltrim',
			func_get_args()
		);
	}

	/* Convert a string's first character to lower case. */
	public static function lcfirst($s) {
		return lcfirst($s);
	}

	/* Replace all non-overlapping instances of a substring with another
	 * string. If `$count` is given, it is assigned the number of
	 * replacements performed. */
	public static function replace($s, $old, $new, &$count = null) {
		if(func_num_args() <= 3) {
			return str_replace($old, $new, $s);
		} else {
			return str_replace($old, $new, $s, $count);
		}
	}

	/* Like `replace` but case-insensitive. */
	public static function ireplace($s, $old, $new, &$count = null) {
		if(func_num_args() <= 3) {
			return str_ireplace($old, $new, $s);
		} else {
			return str_ireplace($old, $new, $s, $count);
		}
	}

	/* Pad the beginning and end of a string with another string so that
	 * the result is `$n` characters long. */
	public static function pad($s, $n, $pad = ' ') {
		return str_pad($s, $n, $pad, STR_PAD_BOTH);
	}

	/* Like `pad` but padding is only applied to the beginning. */
	public static function lpad($s, $n, $pad = ' ') {
		return str_pad($s, $n, $pad, STR_PAD_LEFT);
	}

	/* Like `pad` but padding is only applied to the end. */
	public static function rpad($s, $n, $pad = ' ') {
		return str_pad($s, $n, $pad, STR_PAD_RIGHT);
	}

	/* Repeat a string `$n` times. */
	public static function repeat($s, $n) {
		return str_repeat($s, $n);
	}

	/* Return the part of a string starting with another string, or null
	 * if it does not contain the string. */
	public static function starting_with($s, $substr) {
		return strstr($s, $substr);
	}

	/* Like `starting_with` but case-insenstive. */
	public static function istarting_with($s, $substr) {
		return stristr($s, $substr);
	}

	/* Return the part of a string before a certain substring, or null if
	 * it does not contain the string. */
	public static function before($s, $substr) {
		return strstr($s, $substr, true);
	}

	/* Like `before` but case-insensitive. */
	public static function ibefore($s, $substr) {
		return stristr($s, $substr, true);
	}

	/* Split a string into words (same rules as `word_count`). */
	public static function words($s, $chars = null) {
		return str_word_count($s, 1, $chars);
	}

	/* Count the number of words in a string. What constitues as a word is
	 * defined by the current locale. Optionally provide a string of
	 * additional characters to consider as word characters. */
	public static function word_count($s, $chars = null) {
		return str_word_count($s, 0, $chars);
	}

	/* Return an array mapping the starting indexes of words to their
	 * corresponding words. */
	public static function word_indexes($s, $chars = null) {
		return str_word_count($s, 2, $chars);
	}

	/* Lexicographically compare two strings. Return a negative number if
	 * `$a` comes before `$b`, 0 if they are the same, and a number greater
	 * than 0 if `$a` comes after `$b`. */
	public static function cmp($a, $b) {
		return strcmp($a, $b);
	}

	/* Like `cmp` but case-insensitive. */
	public static function icmp($a, $b) {
		return strcasecmp($a, $b);
	}

	/* Like `cmp` but dependent on the current locale. */
	public static function lcmp($a, $b) {
		return strcoll($a, $b);
	}

	/* Tell whether a string includes a certain substring. Optionally
	 * provide a starting offset. */
	public static function has($s, $substr, $offset = 0) {
		return strpos($s, $substr, $offset) !== false;
	}

	/* Like `has` but case-insensitive. */
	public static function ihas($s, $substr, $offset = 0) {
		return stripos($s, $substr, $offset) !== false;
	}

	/* Get the starting offset of a substring within a string, or null if
	 * it does not appear within the string. Optionally provide a starting
	 * offset. */
	public static function find($s, $substr, $offset) {
		$r = strpos($s, $substr, $offset);
		return $r === false ? null : $r;
	}

	/* Like `find` but case-insensitive. */
	public static function ifind($s, $substr, $offset) {
		$r = stripos($s, $substr, $offset);
		return $r === false ? null : $r;
	}

	/* Escape a string by adding backslashes in front of certain
	 * characters and encoding non-printable characters with octal codes,
	 * just like in C string literals. */
	public static function escape_c_string($s) {
		return addcslashes($s, "\n\r\t\v\f\"'\\");
	}

	/* Un-escape the contents of a C-style string literal. */
	public static function unescape_c_string($s) {
		return stripcslashes($s);
	}

	/* Escape a string by placing backslashes before special characters as
	 * required by PHP. */
	public static function escape_php_string($s) {
		return addslashes($s);
	}

	/* Remove all backslashes (`\`) from a string. */
	public static function unescape_backslashes($s) {
		return stripslashes($s);
	}

	/* Convert a binary string to a hexadecimal string. */
	public static function encode_hex($s) {
		return bin2hex($s);
	}

	/* Parse a hexadecimal string into binary data. */
	public static function decode_hex($s) {
		return hex2bin($s);
	}

	/* Given an integer, return a string containing the corresponding ASCII
	 * character. */
	public static function from_ascii($n) {
		return chr($n);
	}

	/* Alias for `from_ascii`. */
	public static function chr($n) {
		return chr($n);
	}

	/* Given a string containing a single character, return its
	 * corresponding ASCII code. */
	public static function to_ascii($c) {
		return ord($c);
	}

	/* Alias for `to_ascii`. */
	public static function ord($c) {
		return ord($c);
	}

	/* Insert the string `$end` every `$n` characters into `$s`. */
	public static function insert_line_endings($s, $n, $end) {
		return chunk_split($s, $n, $end);
	}

	/* Get the frequencies of the 256 possible byte values (0 through 255)
	 * in a string as an array. */
	public static function byte_frequencies($s) {
		return count_chars($s);
	}

	/* Get a string of all the unique bytes occurring in a string. */
	public static function unique($s) {
		return count_chars($s, 3);
	}

	/* Return a string of all byte values not occurring in a string. */
	public static function unused_bytes($s) {
		return count_chars($s, 4);
	}

	/* Escape all of the special HTML characters in a string by replacing
	 * them with their corresponding character entities. If `$noquote` is
	 * true, double quote (`"`) will not be escaped. */
	public static function encode_html($s, $noquote = false) {
		return htmlspecialchars(
			$s,
			($noquote ? ENT_NOQUOTES : ENT_COMPAT) | ENT_HTML5,
			'UTF-8'
		);
	}

	/* Alias of `encode_html`. */
	public static function escape_html() {
		return call_user_func_array(
			array('self', 'encode_html'),
			func_get_args()
		);
	}

	/* Inverse of `encode_html`. Note that this will not decode every
	 * possible entity. This does not decode named entities except for
	 * those encoded by `encode_html`, which include `&apos`. It will
	 * decode numeric entities except for those corresponding to non-
	 * printable characters. */
	public static function unencode_html($s) {
		return html_entity_decode($s, ENT_QUOTES | ENT_HTML5);
	}

	/* Return a minimal translation dictionary for escaping special HTML
	 * characters using their HTML5 character entities. If `$noquote` is
	 * true, double quote (`"`) will be omitted. */
	public static function encode_html_dict($noquote = false) {
		return get_html_translation_table(
			HTML_SPECIALCHARS,
			($noquote ? ENT_NOQUOTES : ENT_COMPAT) | ENT_HTML5,
			'UTF-8'
		);
	}

	/* Replace all of the characters in a string with their equivalent
	 * HTML5 named character entities, wherever such entities exist. */
	public static function encode_html_entities($s) {
		return htmlentities(
			$s,
			(ENT_QUOTES | ENT_SUBSTITUTE | ENT_DISALLOWED | ENT_HTML5),
			'UTF-8'
		);
	}

	/* Return the (fairly large) translation dictionary for encoding
	 * characters to named HTML5 character entities wherever such entities
	 * exist. */
	public static function encode_html_entities_dict() {
		return get_html_translation_table(
			HTML_ENTITIES,
			ENT_QUOTES | ENT_HTML5,
			'UTF-8'
		);
	}

	/* Strip HTML and PHP tags from a string. */
	public static function strip_tags($s) {
		return strip_tags($s);
	}

	/* URL-decode and parse a query string into an associative array of
	 * values. Note that this assumes the PHP convention of parameter names
	 * ending with `[]` to denote arrays of values. Also note that this
	 * automatically URL-decodes the query string; it is incorrect to
	 * pass a string which is not URL-encoded. */
	public static function parse_raw_query_string($s) {
		parse_str($s, $result);
		return $result;
	}

	/* Parse a CSV line into an array. Optionally provide a delimiter
	 * character, enclosure (quote) character, and escape character. The
	 * defaults are `,`, `"`, and `\`, respectively. */
	public static function parse_csv(/* $s, $delim, $quote, $escape */) {
		return call_user_func_array(
			'str_getcsv',
			func_get_args()
		);
	}

	/* Compute the MD5 hash of a string. Returns a 16-byte binary
	 * string. */
	public static function md5($s) {
		return md5($s, true);
	}

	/* Like `md5` but convert the result to a hex string. */
	public static function md5_hex($s) {
		return md5($s, false);
	}

	/* Compute the SHA1 hash of a string. Returns a 20-byte binary
	 * string. */
	public static function sha1($s) {
		return sha1($s, true);
	}

	/* Like `sha1` but convert the result to a hex string. */
	public static function sha1_hex($s) {
		return sha1($s, false);
	}

	/* Apply rot13 to a string. */
	public static function rot13($s) {
		return str_rot13($s);
	}

	/* Randomly shuffle the characters of a string. */
	public static function shuffle($s) {
		return str_shuffle($s);
	}

	/* Format a number as a currency value using the current locale. Note
	 * that this requires setting the locale using
	 * `setlocale(LC_MONETARY, $locale)` for some locale that is installed
	 * on the system. */
	public static function format_money($amount) {
		return money_format('%n', $amount);
	}

	/* Format a number with a certain number of decimal places, decimal
	 * point character, and thousands separator. The defaults are 0, ".",
	 * and ",", respectively. */
	public static function format_number(
		/* $number, $decimals, $decimal_point, $thousands_sep */
	) {
		return call_user_func_array(
			'number_format',
			func_get_args()
		);
	}

	/* Compute the Levenshtein distance between two strings, which is the
	 * minimal number of character replacements, insertions, and deletions
	 * necessary to transform `$s1` into `$s2`. Optionally provide values
	 * for the cost of insertions, replacements, and deletions. */
	public static function levenshtein(/* $s1, $s2, [$ins, $repl, $del] */) {
		return call_user_func_array(
			'levenshtein',
			func_get_args()
		);
	}

	public static function split_camel_case($str) {
		return preg_split('/(?<=[^A-Z])(?=[A-Z])/', $str);
	}

	public static function begins_with($str, $prefix) {
		return substr($str, 0, strlen($prefix)) === $prefix;
	}

	public static function ends_with($str, $suffix) {
		return substr($str, -strlen($suffix)) === $suffix;
	}

	public static function escape_url($text) {
		return urlencode($text);
	}

	public static function naive_pluralize($s) {
		// Vowel y
		// -y => -ies
		$result = preg_replace('/([^aeiou])y$/', '$1ies', $s, 1, $count);
		if($count) return $result;

		// Sibilants
		// -s, -z, -x, -j, -sh, -tch, -zh => -ses, -zes, etc.
		// Note that this will fail for hard ch sometimes,
		// as in "polemarchs"
		$result = preg_replace('/([^aeiouy]ch|[sz]h|[szxj])$/', '$1es', $s, 1, $count);
		if($count) return $result;

		// Simple addition of s
		// - => -s
		return $s . 's';
	}

}

?>
