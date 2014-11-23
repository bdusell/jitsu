<?php

/* Get data sent to the server from the client via POST and GET. */
class Request {

	/* Get a GET parameter. */
	public static function get($name, $default = null) {
		return Util::get($_GET, $name, $default);
	}

	/* Get a POST parameter. */
	public static function post($name, $default = null) {
		return Util::get($_POST, $name, $default);
	}

	private static $_FORM = null;

	private static function _form_array() {
		// TODO take care of DELETE
		switch(self::method()) {
		case 'GET':
			return $_GET;
		case 'POST':
			return $_POST;
		default:
			if(self::$_FORM === null) {
				parse_str(file_get_contents('php://input'), self::$_FORM);
			}
			return self::$_FORM;
		}
	}

	/* If called with no arguments, get an array of all the form-encoded
	 * parameters sent in the request according to the request method used.
	 * If the request method is GET, get the parameters from the query
	 * string. If it's anything else, get them from the request body.
	 *
	 * If called with the name of a parameter, return the value of that
	 * parameter. */
	public static function form($name = null, $default = null, $valid = null) {
		$array = self::_form_array();
		return $name === null ? $array : Util::get($array, $name, $default, $valid);
	}

	/* Tell whether the form-encoded parameter by the given name was sent.
	 */
	public static function form_has($name) {
		$array = self::_form_array();
		return isset($array[$name]);
	}

	/* Get a cookie, or null if none by the given name was sent. */
	public static function cookie($name, $default = null, $valid = null) {
		return Util::get($_COOKIE, $name, $default, $valid);
	}

	public static function get_bool($name, $default = false) {
		return Util::bool_cast(self::get($name, $default));
	}

	public static function get_uint($name, $default = null, $min = 0) {
		return Util::uint_cast(self::get($name, $default), $min);
	}

	public static function method() {
		return strtoupper($_SERVER['REQUEST_METHOD']);
	}

	public static function url() {
		return rawurldecode(self::raw_url());
	}

	public static function raw_url() {
		return $_SERVER['REQUEST_URI'];
	}
}

?>
