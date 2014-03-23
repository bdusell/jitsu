<?php

/* Get data sent to the server from the client via POST and GET. */
class Param {

	/* Get a GET parameter, or null if none by the given name was sent. */
	public static function get($name, $default = null, $valid = null) {
		return Util::get($_GET, $name, $default, $valid);
	}

	/* Get a POST parameter, or null if none by the given name was sent. */
	public static function post($name, $default = null, $valid = null) {
		return Util::get($_POST, $name, $default, $valid);
	}

	public static function get_bool($name, $default = false) {
		return Util::bool_cast(self::get($name, $default));
	}

	public static function get_uint($name, $default, $min = 0) {
		return Util::uint_cast(self::get($name, $default), $min);
	}

}

?>
