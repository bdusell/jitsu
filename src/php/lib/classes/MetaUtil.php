<?php

class MetaUtil {

	public static function constant_exists($name) {
		return defined($name);
	}

	public static function function_exists($name) {
		return function_exists($name);
	}
}

?>
