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
}

?>
