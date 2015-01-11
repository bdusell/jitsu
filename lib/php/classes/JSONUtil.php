<?php

/* Utilities for dealing with JSON. */
class JSONUtil {

	/* Serialize a PHP object into a string of JSON. The result is
	 * compressed rather than pretty-printed. Note that since empty arrays
	 * in PHP are both valid sequential and associative arrays, it is
	 * ambiguous as to whether they should be encoded as JSON objects or
	 * arrays. To encode an empty JSON array, use an empty PHP array. To
	 * encode an empty JSON object, use an empty instance of `stdClass`
	 * (which can be created with `(object) array()`). Optionally provide
	 * whether to pretty-print the result or not (default is false). */
	public static function encode($obj, $pretty = false) {
		return self::_encode($obj, $pretty);
	}

	/* Like `encode` but pretty print the result with four spaces of
	 * indentation. */
	public static function encode_pretty($obj) {
		return self::_encode($obj, true);
	}

	/* Parse a string of JSON into corresponding PHP objects. JSON objects
	 * are converted into `stdClass` instances. */
	public static function parse($str) {
		$r = json_decode($str);
		if(($code = json_last_error()) !== JSON_ERROR_NONE) {
			throw new RuntimeException(json_last_error_msg(), $code);
		}
		return $r;
	}

	private static function _encode($obj, $pretty) {
		$r = json_encode(
			$obj,
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE |
			($pretty ? JSON_PRETTY_PRINT : 0)
		);
		if($r === false) {
			throw new RuntimeException(json_last_error_msg(), json_last_error());
		}
		return $r;
	}
}

?>
