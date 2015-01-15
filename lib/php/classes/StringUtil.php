<?php

class StringUtil {

	/* Return the length of (i.e., the number of bytes in) a string. */
	public static function length($s) {
		return strlen($s);
	}

	/* Alias of `length`. */
	public static function size($s) {
		return strlen($s);
	}

	/* Return whether two strings are equal. */
	public static function equal($a, $b) {
		return strcmp($a, $b) == 0;
	}

	/* Like `equal` but case-insensitive. */
	public static function iequal($a, $b) {
		return strcasecmp($a, $b) == 0;
	}

	/* Return the characters of a string as an array. */
	public static function chars($s) {
		return str_split($s);
	}

	/* Split a string into chunks of `$n` characters each as a sequential
	 * array. The last chunk may have fewer than `$n` characters. */
	public static function chunks($s, $n) {
		return str_split($s, $n);
	}

	/* Split a string by a delimiter into an array of strings. If `$limit`
	 * is null, then all possible splits are made. If `$limit` is a
	 * positive integer, at most `$limit` splits will be made from the
	 * beginning of the string, with the rest of the string occupying the
	 * last element, so that no more than `$limit` + 1 elements will be
	 * returned. If `$limit` is negative, all parts except for the last
	 * -`$limit` are returned. If `$delim` is null, the string is tokenized
	 * on whitespace characters (`$limit` is ignored in this case... for
	 * now). */
	public static function split($s, $delim = null, $limit = null) {
		if($delim === null) {
			return self::tokenize($s, " \n\t\r\v\f");
		} else {
			if($limit === null) {
				return explode($delim, $s);
			} else {
				return explode($delim, $s, $limit + ($limit >= 0));
			}
		}
	}

	/* Split a string into tokens, where all characters in `$chars` count
	 * as delimiting characters. */
	public static function tokenize($s, $chars) {
		$result = array();
		$r = strtok($s, $chars);
		while($r !== false) {
			$result[] = $r;
			$r = strtok($chars);
		}
		return $result;
	}

	/* Join array elements into a single string with a separator (default
	 * is empty string). */
	public static function join(/* $sep, $strs | $strs */) {
		return call_user_func_array(
			'implode',
			func_get_args()
		);
	}

	/* Strip whitespace and null bytes from the beginning and end of a
	 * string. Optionally provide a string listing the characters to strip
	 * instead. */
	public static function trim($s, $chars = null) {
		return trim($s, $chars);
	}

	/* Like `trim`, but only strips characters from the end. */
	public static function rtrim($s, $chars = null) {
		return rtrim($s, $chars);
	}

	/* Like `trim`, but only strips characters from the beginning. */
	public static function ltrim($s, $chars = null) {
		return ltrim($s, $chars);
	}

	/* Convert a string to lower case. */
	public static function lower($s) {
		return strtolower($s);
	}

	/* Convert a string to upper case. */
	public static function upper($s) {
		return strtoupper($s);
	}

	/* Convert a string's first character to lower case. */
	public static function lcfirst($s) {
		return lcfirst($s);
	}

	/* Alias for `lcfirst`. */
	public static function lower_first($s) {
		return lcfirst($s);
	}

	/* Convert a string's first character to upper case. */
	public static function ucfirst($s) {
		return ucfirst($s);
	}

	/* Alias for `ucfirst`. */
	public static function upper_first($s) {
		return ucfirst($s);
	}

	/* Convert any alphabetic character that appears after whitespace to
	 * upper case. */
	public static function ucwords($s) {
		return ucwords($s);
	}

	/* Alias for `ucwords`. */
	public static function upper_words($s) {
		return ucwords($s);
	}

	/* Replace all non-overlapping instances of a substring with another
	 * string. If `$count` is given, it is assigned the number of
	 * replacements performed. */
	public static function replace($s, $old, $new, &$count = null) {
		return str_replace($old, $new, $s, $count);
	}

	/* Like `replace` but case-insensitive. */
	public static function ireplace($s, $old, $new, &$count = null) {
		return str_ireplace($old, $new, $s, $count);
	}

	/* Translate characters in a string. Characters in `$old` are changed
	 * to the corresponding characters in `$new`. */
	public static function translate($s, $old, $new) {
		return strtr($s, $old, $new);
	}

	/* Get a substring of a string given an offset and length. If a length
	 * is not given, the substring runs to the end of the string. */
	public static function substring($s, $offset, $length = null) {
		return substr($s, $offset, $length);
	}

	/* Replace a portion of a string with another. */
	public static function replace_substring($s, $new, $offset, $length = null) {
		return substr_replace($s, $new, $offset, $length);
	}

	/* Get a substring of a string given a beginning and ending index.
	 * Negative indexes, indicating characters from the end of the string,
	 * may be used. If the start index occurs after the end index, an empty
	 * string will be returned. */
	public static function slice($s, $i, $j = null) {
		list($offset, $len) = Util::convert_slice_indexes($i, $j, strlen($s));
		return substr($s, $offset, $len);
	}

	/* Replace a slice of a string with another. If `$j` is null, the slice
	 * is until the end of the string. */
	public static function replace_slice($s, $i, $j, $new) {
		list($offset, $len) = Util::convert_slice_indexes($i, $j, strlen($s));
		return substr_replace($s, $new, $offset, $len);
	}

	/* Insert a string at a given offset in the string. */
	public static function insert($s, $new, $offset) {
		return substr_replace($s, $new, $offset, 0);
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

	/* "Wrap" a string to a certain number of columns by inserting a string
	 * every `$cols` characters. Inserts newlines by default. */
	public static function wrap($s, $cols, $sep = "\n") {
		return wordwrap($s, $cols, $sep, true);
	}

	/* Repeat a string `$n` times. */
	public static function repeat($s, $n) {
		return str_repeat($s, $n);
	}

	/* Reverse a string. */
	public static function reverse($s) {
		return strrev($s);
	}

	/* Return the part of a string starting with another string, or null
	 * if it does not contain the string. */
	public static function starting_with($s, $substr) {
		$r = strstr($s, $substr);
		return $r === false ? null : $r;
	}

	/* Like `starting_with` but case-insenstive. */
	public static function istarting_with($s, $substr) {
		$r = stristr($s, $substr);
		return $r === false ? null : $r;
	}

	/* Return the last part of a string starting with a certain character,
	 * or null if it does not contain that character. Note that `$char` is
	 * a single character, not a string. */
	public static function rstarting_with($s, $char) {
		$r = strrchr($s, $char);
		return $r === false ? null : $r;
	}

	/* Like `starting_with`, except that `$chars` is a string listing
	 * characters to look for. */
	public static function starting_with_chars($s, $chars) {
		$r = strpbrk($s, $chars);
		return $r === false ? null : $r;
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

	/* Split a string into words. What constitutes as word characters is
	 * defined by the current locale. Optionally provide a string of
	 * additional characters to consider as word characters. */
	public static function words($s, $chars = null) {
		return str_word_count($s, 1, $chars);
	}

	/* Count the number of words in a string (same rules as `words`). */
	public static function word_count($s, $chars = null) {
		return str_word_count($s, 0, $chars);
	}

	/* Return an array mapping the starting indexes of words to their
	 * corresponding words (same rules as `words`). */
	public static function word_indexes($s, $chars = null) {
		return str_word_count($s, 2, $chars);
	}

	/* Wrap words in a string. Long words are split. Optionally provide a
	 * character other than newline to terminate lines. */
	public static function word_wrap($s, $width, $sep = "\n") {
		return wordwrap($s, $width, $sep);
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

	/* Like `cmp` but only checks the first `$n` characters. */
	public static function ncmp($a, $b, $n) {
		return strncmp($a, $b, $n);
	}

	/* Like `ncmp` but case-insensitive. */
	public static function incmp($a, $b, $n) {
		return strncasecmp($a, $b, $n);
	}

	/* Like `cmp` but dependent on the current locale. */
	public static function locale_cmp($a, $b) {
		return strcoll($a, $b);
	}

	/* Like `cmp` but orders strings in a way that seems more natural for
	 * human viewers (e.g. numbers are sorted in increasing order). */
	public static function human_cmp($a, $b) {
		return strnatcmp($a, $b);
	}

	/* Like 'human_cmp' but case-insensitive. */
	public static function human_icmp($a, $b) {
		return strnatcasecmp($a, $b);
	}

	/* Like `cmp` but uses only a substring of the first string in the
	 * comparison. Use a null length to compare to the end of the
	 * string. */
	public static function substring_cmp($a, $offset, $length, $b) {
		return substr_compare($a, $b, $offset, $length);
	}

	/* Like `substring_cmp` but case-insensitive. */
	public static function substring_icmp($a, $offset, $length, $b) {
		return substr_compare($a, $b, $offset, $length, true);
	}

	/* Tell whether a string includes a certain substring. Optionally
	 * provide a starting offset. */
	public static function contains($s, $substr, $offset = 0) {
		return strpos($s, $substr, $offset) !== false;
	}

	/* Like `contains` but case-insensitive. */
	public static function icontains($s, $substr, $offset = 0) {
		return stripos($s, $substr, $offset) !== false;
	}

	/* Tell whether a string includes one of the characters listed in
	 * `$chars`. */
	public static function contains_chars($s, $chars) {
		return strpbrk($s, $chars) !== false;
	}

	/* Alias for `contains_chars`. */
	public static function contains_char($s, $char) {
		return strpbrk($s, $char) !== false;
	}

	/* Tell whether a string begins with a certain prefix. */
	public static function begins_with($str, $prefix) {
		return substr_compare($str, $prefix, 0, strlen($prefix)) == 0;
	}

	/* Like `begins_with` but case-insensitive. */
	public static function ibegins_with($str, $prefix) {
		return substr_compare($str, $prefix, 0, strlen($prefix), true) == 0;
	}

	/* Tell whether a string ends with a certain suffix. */
	public static function ends_with($str, $suffix) {
		return substr_compare($str, $suffix, -strlen($suffix)) == 0;
	}

	/* Like `ends_with` but case-insensitive. */
	public static function iends_with($str, $suffix) {
		return substr_compare($str, $suffix, -strlen($suffix), null, true) == 0;
	}

	/* Get the starting offset of a substring within a string, or null if
	 * it does not appear within the string. Optionally provide a starting
	 * offset. */
	public static function find($s, $substr, $offset = 0) {
		$r = strpos($s, $substr, $offset);
		return $r === false ? null : $r;
	}

	/* Like `find` but case-insensitive. */
	public static function ifind($s, $substr, $offset = 0) {
		$r = stripos($s, $substr, $offset);
		return $r === false ? null : $r;
	}

	/* Like `find` but starts from the end of the string. The offset may
	 * also be negative, indicating where to start from the end of the
	 * string. */
	public static function rfind($s, $substr, $offset = null) {
		if($offset === 0) return strlen($substr) == 0 ? 0 : null;
		$r = strrpos($s, $substr, $offset);
		return $r === false ? null : $r;
	}

	/* Return whether all characters in a string are lower case. */
	public static function is_lower($s) {
		return ctype_lower($s);
	}

	/* Return whether all characters in a string are upper case. */
	public static function is_upper($s) {
		return ctype_upper($s);
	}

	/* Return whether all characters in a string are alphanumeric. */
	public static function is_alphanumeric($s) {
		return ctype_alnum($s);
	}

	/* Return whether all characters in a string are alphabetic. */
	public static function is_alphabetic($s) {
		return ctype_alpha($s);
	}

	/* Return whether all characters in a string are control characters. */
	public static function is_control($s) {
		return ctype_cntrl($s);
	}

	/* Return whether all characters in a string are decimal digits. */
	public static function is_decimal($s) {
		return ctype_digit($s);
	}

	/* Return whether all characters in a string are hexadecimal digits. */
	public static function is_hex($s) {
		return ctype_xdigit($s);
	}

	/* Return whether all characters in a string are visible characters (no
	 * whitespace or control characters). */
	public static function is_visible($s) {
		return ctype_graph($s);
	}

	/* Return whether all characters in a string have printable output (no
	 * control characters). */
	public static function is_printable($s) {
		return ctype_print($s);
	}

	/* Return whether all characters in a string are punctuation. */
	public static function is_punctuation($s) {
		return ctype_punct($s);
	}

	/* Return whether all characters in a string are whitespace. */
	public static function is_whitespace($s) {
		return ctype_space($s);
	}

	/* Count the number of times a string contains a substring, excluding
	 * overlaps. Optionally provide a starting offset and length. */
	public static function count($s, $substr, $offset = 0, $length = null) {
		if($length !== null) {
			$length = min(strlen($s) - $offset, $length);
		}
		return substr_count($s, $substr, $offset, $length);
	}

	/* Get the length of the initial segment of a string which contains
	 * only the characters listed in `$chars`. Optionally provide a
	 * range of indexes to check. */
	public static function span($s, $chars, $begin = null, $end = null) {
		return strspn($s, $chars, $begin, $end);
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

	/* Remove all backslash (`\`) escape characters from a string. Note
	 * that this does not decode `\n` as a newline, '\t' as tab, etc.,
	 * but as the literal characters `n`, `t`, etc. */
	public static function unescape_backslashes($s) {
		return stripslashes($s);
	}

	/* Parse a string to an integer according to a certain base. If `$base`
	 * is null, the base is deduced from the prefix of the string (`0x` for
	 * hexadecimal, `0` for octal, and decimal otherwise). Ignore any
	 * invalid trailing characters. */
	public static function parse_int($s, $base = null) {
		return intval($s, $base === null ? 0 : $base);
	}

	/* Parse a floating-point value. Throw `RuntimeException` if `$s` is
	 * not a valid float string. */
	public static function parse_real($s) {
		if(is_numeric($s)) {
			return floatval($s);
		} else {
			throw new RuntimeException('invalid real number string');
		}
	}

	/* Convert a binary string to a hexadecimal string. */
	public static function encode_hex($s) {
		return bin2hex($s);
	}

	/* Parse a hexadecimal string into binary data. */
	public static function decode_hex($s) {
		return hex2bin($s);
	}

	/* Encode a binary string in base 64. */
	public static function encode_base64($s) {
		return base64_encode($s);
	}

	/* Decode a base 64 string to binary. */
	public static function decode_base64($s) {
		return base64_decode($s);
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

	/* Get the frequencies of the 256 possible byte values (0 through 255)
	 * in a string as an array. */
	public static function count_bytes($s) {
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

	/* Inverse of `encode_html`. The term "unencode" is used here as
	 * opposed to "decode" to emphasize the fact that this function is not
	 * suitable for decoding arbitrary HTML code, but rather HTML encoded
	 * by `encode_html` using only a small set of named character entity
	 * codes. This function does not recognize named entities except for
	 * those encoded by `encode_html` as well as `&apos;`. It will decode
	 * numeric entities except for those corresponding to non-printable
	 * characters, which it will leave encoded. */
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
	 * HTML5 named character entities, wherever such entities exist. This
	 * particular ability is rarely useful, and `encode_html` should be
	 * preferred instead for efficiency. */
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
	 * ending with `[]` to denote arrays of values; in cases where
	 * parameters share the same name, only the last one is included. Also
	 * note that this automatically URL-decodes the query string; it is
	 * incorrect to pass a string which is not URL-encoded. */
	public static function parse_raw_query_string($s) {
		parse_str($s, $result);
		return $result;
	}

	/* Generate and URL-encode a query string given an array or object of
	 * data. Optionally provide a separator instead of `&`, e.g. `;`. */
	public static function encode_standard_query_string($data, $sep = '&') {
		return http_build_query($data, '', $sep, PHP_QUERY_RFC3986);
	}

	/* Like `encode_standard_query_string`, but encode spaces with `+`.
	 * This should be preferred for compatibility reasons. */
	public static function encode_query_string($data, $sep = '&') {
		return http_build_query($data, '', $sep);
	}

	/* URL-encode a string. */
	public static function encode_standard_url($s) {
		return rawurlencode($s);
	}

	/* Decode a URL-encoded string. */
	public static function decode_standard_url($s) {
		return rawurldecode($s);
	}

	/* URL-encode a string, and use `+` to encode spaces. This should be
	 * preferred for compatibility reasons. */
	public static function encode_url($s) {
		return urlencode($s);
	}

	/* Decode a URL-encoded string, treating `+` as space. This should be
	 * preferred for compatibility reasons. */
	public static function decode_url($s) {
		return urldecode($s);
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

	/* Apply rot13 encryption to a string. */
	public static function rot13($s) {
		return str_rot13($s);
	}

	/* Randomly shuffle the characters of a string. */
	public static function shuffle($s) {
		return str_shuffle($s);
	}

	/* Format a number as a currency value using the current locale. Note
	 * that this requires setting the locale using
	 * `setlocale(LC_ALL, $locale)` or `setlocale(LC_MONETARY, $locale)`
	 * for some locale that is installed on the system. */
	public static function format_money($amount) {
		return money_format('%n', $amount);
	}

	/* Format a number with a certain number of decimal places, decimal
	 * point character, and thousands separator. The defaults are 0, ".",
	 * and ",", respectively. */
	public static function format_number(
		/* $number, $decimals, $decimal_point, $thousands_sep */)
	{
		return call_user_func_array(
			'number_format',
			func_get_args()
		);
	}

	/* Compute the Levenshtein distance between two strings, which is the
	 * minimal number of character replacements, insertions, and deletions
	 * necessary to transform `$s1` into `$s2`. Optionally provide values
	 * for the costs of insertions, replacements, and deletions. */
	public static function levenshtein(/* $s1, $s2, [$ins, $repl, $del] */) {
		return call_user_func_array(
			'levenshtein',
			func_get_args()
		);
	}

	/* Split a string in camel case into its components. Runs of
	 * consecutive capital letters are treated as acronyms and are grouped
	 * accordingly. For example, the string "XMLHttpRequest" will be split
	 * into "XML", "Http", "Request". */
	public static function split_camel_case($str) {
		return preg_split('/(?<=[^A-Z])(?=[A-Z])|(?<=[A-Z])(?=[A-Z][a-z])/', $str);
	}

	/* Convert an English word to its plural or "-s" form (one could also
	 * use this to form the third person singular form of a verb) using a
	 * naive algorithm. This will not work for irregular forms and certain
	 * other cases, but it knows enough to convert common endings like "-y"
	 * to "-ies", "-s" to "-ses", and so on. */
	public static function naive_pluralize($s) {
		// Vowel y
		// -y => -ies
		$result = preg_replace('/([^aeiou])y$/', '$1ies', $s, 1, $count);
		if($count) return $result;

		// Sibilants
		// -s, -z, -x, -j, -sh, -tch, -zh => -ses, -zes, etc.
		// Note that this will fail for hard ch sometimes,
		// as in the word "hierarchs"
		$result = preg_replace('/([^aeiouy]ch|[sz]h|[szxj])$/', '$1es', $s, 1, $count);
		if($count) return $result;

		// Simple addition of s
		// - => -s
		return $s . 's';
	}

	/* Use PHP output buffering to capture all output printed in a callback
	 * function and return the result as a string. */
	public static function capture($callback) {
		ob_start();
		call_user_func($callback);
		return ob_get_clean();
	}
}

?>
