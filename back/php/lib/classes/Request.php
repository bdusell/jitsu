<?php

/* Get information about the request received from the client. */
class Request {

	/* Get the HTTP method used in the request, _always_ in upper case
	 * (e.g. `'GET'`, `'PUT'`, etc.). */
	public static function method() {
		static $result = null;
		if($result === null) {
			$result = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		return $result;
	}

	/* Get the requested URL, decoded. See `raw_url`. */
	public static function url() {
		static $result = null;
		if($result === null) {
			$result = rawurldecode(self::raw_url());
		}
		return $result;
	}

	/* Get the requested URL, un-decoded. This consists of the part of the
	 * URI after the authority (after the `.com`, etc. in a typical URL),
	 * _including_ the query string. */
	public static function raw_url() {
		return $_SERVER['REQUEST_URI'];
	}

	/* Get the path part of the requested URI, decoded. This is the part
	 * between the authority and the query string. */
	public static function path() {
		self::_parse_url();
		return self::$_path;
	}

	/* Get the query string of the requested URI, decoded. */
	public static function query_string() {
		self::_parse_url();
		return self::$_query_string;
	}

	/* Get the raw query string of the requested URI. */
	public static function raw_query_string() {
		return $_SERVER['QUERY_STRING'];
	}

	/* Get form-encoded parameters from the current request.
	 *
	 * If called with no arguments, returns an array mapping the names of
	 * the form-encoded parameters sent in the request to their values. All
	 * keys and values are decoded. The parameters are taken from the
	 * appropriate part of the request based on the HTTP method used and
	 * RESTful conventions. For GET and DELETE, they are parsed from the
	 * query string. For everything else, they are parsed from the request
	 * body. The parsed form is cached.
	 *
	 * If called with the name of a parameter, returns the value of that
	 * single parameter, or `$default` if it does not exist.
	 */
	public static function form($name = null, $default = null) {
		static $form = null;
		if($form === null) {
			switch(self::method()) {
			case 'GET':
				$form = $_GET;
				break;
			case 'POST':
				$form = $_POST;
				break;
			case 'DELETE':
				/* Note that parse_str automatically decodes
				 * the result, so be sure to use the raw
				 * query string. */
				parse_str(self::raw_query_string(), $form);
				break;
			default:
				parse_str(self::slurp_input(), $form);
				break;
			}
		}
		return $name === null ? $form : Util::get($form, $name, $default);
	}

	/* Get cookies sent with the current request.
	 *
	 * Like `form`, if called with no arguments, returns an array mapping
	 * cookie names to their values. If called with the name of a cookie,
	 * returns the value of that single cookie, or `$default` if it does
	 * not exist.
	 */
	public static function cookie($name = null, $default = null) {
		return $name === null ? $_COOKIE : Util::get($_COOKIE, $name, $default);
	}

	/* Get the raw input sent in the request body as a single string. The
	 * result is cached, so calling this function more than once is fine.
	 */
	public static function slurp_input() {
		static $result = null;
		if($result === null) {
			$result = file_get_contents('php://input');
		}
		return $result;
	}

	private static $_parsed_url = false;
	private static $_path = null;
	private static $_query_string = null;

	private static function _parse_url() {
		if(!self::$_parsed_url) {
			$parts = parse_url(self::url());
			self::$_path = Util::get($parts, 'path');
			self::$_query_string = Util::get($parts, 'query');
			self::$_parsed_url = true;
		}
	}
}

?>