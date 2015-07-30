<?php

namespace jitsu;

/* Get information about the current request being processed. */
class Request {

	public static function scheme() {
		return $_SERVER['REQUEST_SCHEME'];
	}

	public static function protocol() {
		return $_SERVER['SERVER_PROTOCOL'];
	}

	public static function host() {
		return $_SERVER['HTTP_HOST'];
	}

	public static function method() {
		static $result = null;
		if($result === null) {
			$result = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		return $result;
	}

	public static function uri() {
		return $_SERVER['REQUEST_URI'];
	}

	public static function path() {
		static $result = null;
		if($result === null) {
			$result = parse_url(self::uri(), PHP_URL_PATH);
		}
		return $result;
	}

	public static function query_string() {
		return $_SERVER['QUERY_STRING'];
	}

	public static function form($name = null) {
		/* TODO Handle case where POST size is exceeded. */
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
				parse_str(self::query_string(), $form);
				break;
			default:
				// PUT, PATCH
				parse_str(self::body(), $form);
				break;
			}
		}
		return $name === null ? $form : \jitsu\Util::get($form, $name);
	}

	public static function header($name = null) {
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
					$apache_good = function_exists('apache_request_headers');
					if($apache_good) {
						$apache_headers = apache_request_headers();
						$apache_good = $apache_headers !== false;
						if($apache_good) {
							$headers = array_change_key_case(
								$apache_headers
							);
							$headers_fetched = true;
							return \jitsu\Util::get($headers, $name);
						}
					}
				}
				return null;
			}
		} else {
			if(!$headers_fetched) {
				if($apache_good === null) {
					$apache_good = function_exists('apache_request_headers');
					if($apache_good) {
						$apache_headers = apache_request_headers();
						$apache_good = $apache_headers !== false;
						if($apache_good) {
							$headers = array_change_key_case(
								$apache_headers
							);
						}
					}
				}
				if(!$apache_good) {
					foreach($_SERVER as $k => $v) {
						if(strncmp($k, 'HTTP_', 5) == 0) {
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

	public static function cookie($name = null) {
		return $name === null ? $_COOKIE : \jitsu\Util::get($_COOKIE, $name);
	}

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

	public static function file($name = null) {
		return $name === null ? $_FILES : \jitsu\Util::get($_FILES, $name);
	}

	public static function files() {
		return $_FILES;
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
					throw new \RuntimeException('unable to save uploaded file');
				}
			} else {
				throw new \RuntimeException(self::file_error_message($error), $error);
			}
		} else {
			throw new \RuntimeException('no file uploaded under parameter "' . $name . '"');
		}
		$info = $_FILES[$name];
	}

	/* Get the IP address of the remote endpoint. */
	public static function origin_ip_address() {
		return $_SERVER['REMOTE_ADDR'];
	}

	/* Get the port number of the remote endpoint. */
	public static function origin_port() {
		return $_SERVER['REMOTE_PORT'];
	}

	/* Timestamp of the start of the request. */
	public static function timestamp() {
		return $_SERVER['REQUEST_TIME'];
	}

	private static function file_error_message($code) {
		switch($code) {
		case UPLOAD_ERR_OK:
			return 'no error';
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			return 'uploaded file is too large';
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

	private static function _header_to_env($name) {
		return strtoupper(str_replace('-', '_', $name));
	}

	private static function _env_to_header($name) {
		return strtolower(str_replace('_', '-', $name));
	}
}

?>
