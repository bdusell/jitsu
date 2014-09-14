<?php

class FuncUtil {

	/* A comparison function defining a total ordering for integers. */
	public static function int_cmp($a, $b) {
		return $a - $b;
	}

	/* A comparison function defining a total ordering for strings. */
	public static function string_cmp($a, $b) {
		return strcmp($a, $b);
	}

	private $_int_number = 0;
	private $_string_number = 1;

	private static function _key_type_number($v) {
		return is_int($v) ? self::$_int_number : self::$_string_number;
	}

	/* A comparison function defining a total ordering for integers and
	 * strings (valid PHP array keys), using strict comparison. */
	public static function key_cmp($a, $b) {
		$value = self::int_cmp(
			$type = self::_key_type_number($a),
			self::_key_type_number($b)
		);
		if($value == 0) {
			if($type == self::$_int_number) {
				return self::int_cmp($a, $b);
			} elseif($type == self::$_string_number) {
				return self::string_cmp($a, $b);
			}
		}
		return $value;
	}
}

?>
