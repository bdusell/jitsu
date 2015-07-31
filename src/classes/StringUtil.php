<?php

namespace jitsu;

/* Utilities for dealing with strings. */
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
		return strcmp($a, $b) === 0;
	}

	/* Like `equal` but case-insensitive. */
	public static function iequal($a, $b) {
		return strcasecmp($a, $b) === 0;
	}

	/* Return the characters of a string as an array. */
	public static function chars($s) {
		if(strlen($s) === 0) return array();
		return str_split($s);
	}

	/* Split a string into chunks of `$n` characters each as a sequential
	 * array. The last chunk may have between 1 and `$n` characters. It is
	 * an error for `$n` not to be greater than 0. */
	public static function chunks($s, $n) {
		if(strlen($s) === 0) return array();
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
	public static function join($sep, $strs = null) {
		if($strs === null) {
			return implode($sep);
		} else {
			return implode($sep, $strs);
		}
	}

	/* Strip whitespace and null bytes from the beginning and end of a
	 * string. Optionally provide a string listing the characters to strip
	 * instead. */
	public static function trim($s, $chars = null) {
		if($chars === null) {
			return trim($s);
		} else {
			return trim($s, $chars);
		}
	}

	/* Like `trim`, but only strips characters from the end. */
	public static function rtrim($s, $chars = null) {
		if($chars === null) {
			return rtrim($s);
		} else {
			return rtrim($s, $chars);
		}
	}

	/* Like `trim`, but only strips characters from the beginning. */
	public static function ltrim($s, $chars = null) {
		if($chars === null) {
			return ltrim($s);
		} else {
			return ltrim($s, $chars);
		}
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
	 * string. */
	public static function replace($s, $old, $new) {
		if(strlen($old) === 0) {
			if(strlen($s) === 0) {
				return $new;
			} else {
				return $new . implode($new, str_split($s)) . $new;
			}
		} else {
			return str_replace($old, $new, $s);
		}
	}

	/* Like `replace`, but also return the number of replacements. Returns
	 * an array `array($result, $count)`. */
	public static function count_replace($s, $old, $new) {
		if(strlen($old) === 0) {
			if(strlen($s) === 0) {
				return array($new, 1);
			} else {
				return array(
					$new . implode($new, str_split($s)) . $new,
					strlen($s) + 1
				);
			}
		} else {
			$r = str_replace($old, $new, $s, $count);
			return array($r, $count);
		}
	}

	/* Like `replace` but case-insensitive. */
	public static function ireplace($s, $old, $new) {
		if(strlen($old) === 0) {
			if(strlen($s) === 0) {
				return $new;
			} else {
				return $new . implode($new, str_split($s)) . $new;
			}
		} else {
			return str_ireplace($old, $new, $s);
		}
	}

	/* Like `count_replace` but case-insensitive. */
	public static function count_ireplace($s, $old, $new) {
		if(strlen($old) === 0) {
			if(strlen($s) === 0) {
				return array($new, 1);
			} else {
				return array(
					$new . implode($new, str_split($s)) . $new,
					strlen($s) + 1
				);
			}
		} else {
			$r = str_ireplace($old, $new, $s, $count);
			return array($r, $count);
		}
	}

	/* Replace all non-overlapping instances of the keys of `$pairs` with
	 * their corresponding values. Longer keys have precedence. */
	public static function replace_multiple($s, $pairs) {
		return strtr($s, $pairs);
	}

	/* Translate characters in a string. Characters in `$old` are changed
	 * to the corresponding characters in `$new`. */
	public static function translate($s, $old, $new) {
		return strtr($s, $old, $new);
	}

	/* Get a substring of a string given an offset and length. If the
	 * length is null, the substring runs to the end of the string. If the
	 * offset is greater than the length of the string, the result is an
	 * empty string. A negative offset denotes an offset from the end of
	 * the string. If the length is negative, the result will be an empty
	 * string. */
	public static function substring($s, $offset, $length = null) {
		$n = strlen($s);
		if($offset >= $n) return '';
		if($length === null) {
			return substr($s, $offset);
		} else {
			if($n + $offset < 0) {
				$length = $n + $offset + $length;
			}
			if($length < 0) return '';
			return substr($s, $offset, $length);
		}
	}

	/* Replace a portion of a string with another. */
	public static function replace_substring($s, $new, $offset, $length = null) {
		$n = strlen($s);
		if($offset >= $n) return $s . $new;
		if($length === null) {
			return substr_replace($s, $new, $offset);
		} else {
			if($n + $offset < 0) {
				$length = $n + $offset + $length;
			}
			if($length < 0) $length = 0;
			return substr_replace($s, $new, $offset, $length);
		}
	}

	/* Get a substring of a string given a beginning and non-inclusive
	 * ending index. Negative indexes denote offsets from the end of the
	 * string. If the start index occurs after the end index, an empty
	 * string is returned. If the end index is null, the slice runs to the
	 * end of the string. */
	public static function slice($s, $i, $j = null) {
		return self::substring($s, $i, self::_slice_len($s, $i, $j));
	}

	/* Replace a slice of a string with another. If the ending index comes
	 * after the starting index, the replacement is inserted at the
	 * starting index. */
	public static function replace_slice($s, $new, $i, $j = null) {
		return self::replace_substring($s, $new, $i, self::_slice_len($s, $i, $j));
	}

	private static function _slice_len($s, $i, $j) {
		if($j === null) {
			return null;
		} elseif($j < 0) {
			if($i < 0) {
				return $j - $i;
			} else {
				return strlen($s) + $j - $i;
			}
		} else {
			if($i < 0) {
				return $j - (strlen($s) + $i);
			} else {
				return $j - $i;
			}
		}
	}

	/* Insert a string at a given offset in the string. A negative offset
	 * denotes an offset from the end of the string. */
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
		if(strlen($substr) === 0) return $s;
		$r = strstr($s, $substr);
		return $r === false ? null : $r;
	}

	/* Like `starting_with` but case-insenstive. */
	public static function istarting_with($s, $substr) {
		if(strlen($substr) === 0) return $s;
		$r = stristr($s, $substr);
		return $r === false ? null : $r;
	}

	/* Return the last part of a string starting with a certain character,
	 * or null if it does not contain that character. Note that `$char`
	 * should be only a single character. */
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
		if(strlen($substr) === 0) return '';
		$r = strstr($s, $substr, true);
		return $r === false ? null : $r;
	}

	/* Like `before` but case-insensitive. */
	public static function ibefore($s, $substr) {
		if(strlen($substr) === 0) return '';
		$r = stristr($s, $substr, true);
		return $r === false ? null : $r;
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

	private static function _substr_cmp($a, $offset, $length, $b, $flag) {
		if($length === null) {
			$length = strlen($a) + (
				$offset < 0 ? -strlen($a) - $offset : 0
			);
		} elseif($offset < ($n = -strlen($a))) {
			$length = max($length - ($n - $offset), 0);
		}
		if(
			$length == 0 ||
			$offset >= ($an = strlen($a)) ||
			$an === 0 && $offset <= 0
		) {
			return strlen($b) === 0 ? 0 : -1;
		}
		return substr_compare($a, $b, $offset, $length, $flag);
	}

	/* Like `cmp` but uses only a substring of the first string in the
	 * comparison. Use a null length to compare to the end of the
	 * string. A negative offset counts from the end of the string. */
	public static function substring_cmp($a, $offset, $length, $b) {
		return self::_substr_cmp($a, $offset, $length, $b, false);
	}

	/* Like `substring_cmp` but case-insensitive. */
	public static function substring_icmp($a, $offset, $length, $b) {
		return self::_substr_cmp($a, $offset, $length, $b, true);
	}

	/* Tell whether a string includes a certain substring. Optionally
	 * provide a starting offset. */
	public static function contains($s, $substr, $offset = 0) {
		if(strlen($substr) === 0) return true;
		return strpos($s, $substr, $offset) !== false;
	}

	/* Like `contains` but case-insensitive. */
	public static function icontains($s, $substr, $offset = 0) {
		if(strlen($substr) === 0) return true;
		return stripos($s, $substr, $offset) !== false;
	}

	/* Tell whether a string includes one of the characters listed in
	 * the string `$chars`. */
	public static function contains_chars($s, $chars) {
		return strlen($chars) !== 0 && strpbrk($s, $chars) !== false;
	}

	/* Tell whether a string contains a character. */
	public static function contains_char($s, $char) {
		return strpbrk($s, $char) !== false;
	}

	/* Tell whether a string begins with a certain prefix. */
	public static function begins_with($str, $prefix) {
		return strncmp($str, $prefix, strlen($prefix)) === 0;
	}

	/* Like `begins_with` but case-insensitive. */
	public static function ibegins_with($str, $prefix) {
		return strncasecmp($str, $prefix, strlen($prefix)) === 0;
	}

	/* Tell whether a string ends with a certain suffix. */
	public static function ends_with($str, $suffix) {
		if(($n = strlen($suffix)) === 0) {
			return true;
		}
		return self::substring_cmp($str, -$n, null, $suffix) === 0;
	}

	/* Like `ends_with` but case-insensitive. */
	public static function iends_with($str, $suffix) {
		if(($n = strlen($suffix)) === 0) {
			return true;
		}
		return self::substring_icmp($str, -$n, null, $suffix) === 0;
	}

	/* Remove a prefix from a string, or return null if the subject string
	 * does not have that prefix. */
	public static function remove_prefix($str, $prefix) {
		return self::begins_with($str, $prefix) ?
			self::substring($str, strlen($prefix)) : null;
	}

	/* Like `remove_prefix`, but case-insensitive. */
	public static function iremove_prefix($str, $prefix) {
		return self::ibegins_with($str, $prefix) ?
			self::substring($str, strlen($prefix)) : null;
	}

	/* Like `remove_prefix`, but for a suffix instead of a prefix. */
	public static function remove_suffix($str, $suffix) {
		return self::ends_with($str, $suffix) ?
			self::substring($str, 0, strlen($str) - strlen($suffix)) : null;
	}

	/* Like `remove_suffix`, but case-insensitive. */
	public static function iremove_suffix($str, $suffix) {
		return self::iends_with($str, $suffix) ?
			self::substring($str, 0, strlen($str) - strlen($suffix)) : null;
	}

	/* Get the starting offset of a substring within a string, or null if
	 * it does not appear within the string. Optionally provide a starting
	 * offset. */
	public static function find($s, $substr, $offset = 0) {
		return self::_find('strpos', $s, $substr, $offset);
	}

	/* Like `find` but case-insensitive. */
	public static function ifind($s, $substr, $offset = 0) {
		return self::_find('stripos', $s, $substr, $offset);
	}

	private static function _find($name, $s, $substr, $offset) {
		if($offset > strlen($s)) return null;
		if(strlen($substr) === 0) return $offset;
		$r = call_user_func($name, $s, $substr, $offset);
		return $r === false ? null : $r;
	}

	/* Like `find` but starts from the end of the string. The optional
	 * offset is the number of characters from the _end_ of the string. */
	public static function rfind($s, $substr, $offset = 0) {
		if($offset > strlen($s)) return null;
		if(strlen($substr) === 0) return strlen($s) - $offset;
		$r = strrpos($s, $substr, -$offset);
		return $r === false ? null : $r;
	}

	/* Get the first part of a string delimited by a substring, or the
	 * whole string if it does not contain that substring. */
	public static function first_part($s, $substr) {
		if(strlen($substr) === 0) return '';
		$pos = strpos($s, $substr);
		return $pos === false ? $s : substr($s, 0, $pos);
	}

	/* Get the last part of a string delimited by a substring, or the whole
	 * string if it does not contain that substring. */
	public static function last_part($s, $substr) {
		$pos = self::rfind($s, $substr);
		if($pos === null) {
			return $s;
		} else {
			$len = $pos + strlen($substr);
			if($len === strlen($s)) {
				return '';
			} else {
				return substr($s, $len);
			}
		}
	}

	/* Return whether all characters in a string are lower case. False if
	 * `$s` is empty. */
	public static function is_lower($s) {
		return ctype_lower($s);
	}

	/* Return whether all characters in a string are upper case. False if
	 * `$s` is empty. */
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

	/* Return whether all characters in a string are whitespace. False if
	 * `$s` is empty. */
	public static function is_whitespace($s) {
		return ctype_space($s);
	}

	/* Count the number of times a string contains a substring, excluding
	 * overlaps. Optionally provide a starting offset and length. */
	public static function count($s, $substr, $offset = 0, $length = null) {
		if(strlen($substr) === 0) {
			if($offset > strlen($s)) {
				return 0;
			} else {
				$r = strlen($s) - $offset;
				if($length !== null) $r = min($r, $length);
				return $r + 1;
			}
		} else {
			if($offset >= strlen($s)) {
				return 0;
			} else {
				if($length === null) {
					return substr_count($s, $substr, $offset);
				} else {
					$length = min(strlen($s) - $offset, $length);
					if($length === 0) {
						return 0;
					} else {
						return substr_count($s, $substr, $offset, $length);
					}
				}
			}
		}
	}

	/* Get the length of the initial segment of a string which contains
	 * only the characters listed in `$chars`. Optionally provide a
	 * range of indexes to check. */
	public static function span($s, $chars, $begin = 0, $end = null) {
		if($end === null) {
			return strspn($s, $chars, $begin);
		} else {
			return strspn($s, $chars, $begin, $end);
		}
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
		try {
			call_user_func($callback);
		} catch(\Exception $e) {
			ob_end_clean();
			throw $e;
		}
		return ob_get_clean();
	}
}

?>