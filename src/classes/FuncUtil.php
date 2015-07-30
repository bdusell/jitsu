<?php

class FuncUtil {

	public static function compose($f, $g) {
		return function() use($f, $g) {
			return call_user_func($f, call_user_func_array($g, func_get_args()));
		};
	}

	public static function concat($f, $g) {
		return function() use($f, $g) {
			$args = func_get_args();
			call_user_func_array($f, $args);
			return call_user_func_array($g, $args);
		};
	}
}

?>
