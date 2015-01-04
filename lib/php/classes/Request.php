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

	/* Get the path part of the requested URI, decoded. This is the
	 * hierarchical part between the authority and the query string. */
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
				// PUT, PATCH
				parse_str(self::body(), $form);
				break;
			}
		}
		return $name === null ? $form : Util::get($form, $name, $default);
	}

	/* Get headers sent in the current request.
	 *
	 * If called with no arguments, returns an array mapping the names in
	 * lower case of all headers sent in the request to their values.
	 *
	 * If called with the name of a header (case-insensitive), returns its
	 * value, or `$default` if it was not sent.
	 */
	public static function header($name = null, $default = null) {
		static $headers = array();
		static $headers_fetched = false;
		static $apache_good = null;
		if($name !== null) {
			$name = strtolower($name);
			if(array_key_exists($name, $headers)) {
				return $headers[$name];
			} else {
				$key = 'HTTP_' . self::_header_to_env($name);
				if(array_key_exists($key, $_SERVER)) {
					return ($headers[$name] = $_SERVER[$key]);
				} elseif($apache_good === null) {
					$apache_headers = apache_request_headers();
					$apache_good = $apache_headers !== false;
					if($apache_good) {
						$headers = array_change_key_case(
							$apache_headers
						);
						$headers_fetched = true;
						return Util::get($headers, $name, $default);
					}
				}
				return $default;
			}
		} else {
			if(!$headers_fetched) {
				if($apache_good === null) {
					$apache_headers = apache_request_headers();
					$apache_good = $apache_headers !== false;
					if($apache_good) {
						$headers = array_change_key_case(
							$apache_headers
						);
					}
				}
				if(!$apache_good) {
					foreach($_SERVER as $k => $v) {
						if(substr_compare($k, 'HTTP_', 0, 5) == 0) {
							$header_name = self::_env_to_header(
								substr($k, 5)
							);
							$headers[$header_name] = $v;
						}
					}
				}
				$headers_fetched = true;
			}
			return $headers;
		}
	}

	/* Alias for `header()`. */
	public static function headers() {
		return self::header();
	}

	/* Get the content type of the request. */
	public static function content_type() {
		return self::header('Content-Type');
	}

	/* Get a list of the acceptable content types of the response as an
	 * associative array mapping MIME type strings to their respective
	 * quality ratings, ordered in descending order of quality. */
	public static function accept() {
		// TODO
		throw new Exception('not implemented');
	}

	/* Get the HTTP referrer URI or null if it was not sent. */
	public static function referer() {
		return self::header('Referer');
	}

	/* Correctly spelled alias of `referer`. */
	public static function referrer() {
		return self::referer();
	}

	/* Get cookies sent with the current request, parsed and decoded.
	 *
	 * Like `form`, if called with no arguments, returns an array mapping
	 * cookie names to their values. If called with the name of a cookie,
	 * returns the value of that single cookie, or `$default` if it does
	 * not exist. */
	public static function cookie($name = null, $default = null) {
		return $name === null ? $_COOKIE : Util::get($_COOKIE, $name, $default);
	}

	/* Alias for `cookie()`. */
	public static function cookies() {
		return $_COOKIE;
	}

	/* Slurp the raw input sent in the request body into a single string.
	 * The result is cached, so calling this function more than once is
	 * fine. */
	public static function body() {
		static $result = null;
		if($result === null) {
			$result = file_get_contents('php://input');
		}
		return $result;
	}

	/* Get the names of files uploaded in a multipart-formdata request body
	 * and stored in temporary file space via PHP's POST upload mechanism.
	 *
	 * If called with no arguments, returns an array mapping the form
	 * parameter names of all uploaded files to arrays containing
	 * information about each upload.
	 *
	 * If called with the name of a form parameter, returns the info array
	 * for the file uploaded under that name, or `$default` if no file was
	 * uploaded under that name.
	 *
	 * The members of the info array are as follows:
	 *   `name`
	 *     The original name of the file on the client machine.
	 *   `type`
	 *     The MIME type of the file as indicated in the request body (not
	 *     to be trusted for security reasons).
	 *   `size`
	 *     The size in bytes of the uploaded file.
	 *   `tmp_name`
	 *     The temporary filepath of the uploaded file.
	 *   `error`
	 *     Error code for the file upload. See PHP's `UPLOAD_ERR_`
	 *     constants.
	 */
	public static function file($name = null, $default = null) {
		return $name === null ? $_FILES : Util::get($_FILES, $name, $default);
	}

	/* Save a file uploaded under the form parameter `$name` to the path
	 * `$dest_path` on the filesystem. Throws `RuntimeException` if the
	 * file is missing, if there is an error code associated with this file
	 * upload, or if it could not be saved. */
	public static function save_file($name, $dest_path) {
		if(array_key_exists($name, $_FILES)) {
			$info = $_FILES[$name];
			if(($error = $info['error']) === UPLOAD_ERR_OK) {
				if(!move_uploaded_file($info['tmp_name'], $dest_path)) {
					throw new RuntimeException('unable to save uploaded file');
				}
			} else {
				throw new RuntimeException(self::file_error_message($error), $error);
			}
		} else {
			throw new RuntimeException('no file uploaded under parameter "' . $name . '"');
		}
		$info = $_FILES[$name];
	}

	private static function file_error_message($code) {
		switch($code) {
		case UPLOAD_ERR_OK:
			return 'no error';
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			return 'uploaded file is prohibitively large';
		case UPLOAD_ERR_PARTIAL:
			return 'incomplete file upload';
		case UPLOAD_ERR_NO_FILE:
			return 'missing file contents';
		/*
		case UPLOAD_ERR_NO_TMP_DIR:
		case UPLOAD_ERR_CANT_WRITE:
		case UPLOAD_ERR_EXTENSION:
		*/
		default:
			return 'internal error';
		}
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

	private static function _header_to_env($name) {
		return strtoupper(str_replace('-', '_', $name));
	}

	private static function _env_to_header($name) {
		return strtolower(str_replace('_', '-', $name));
	}
}

?>
