<?php

namespace phrame\http;

/* Get information about the current request being processed. */
class CurrentRequest extends AbstractRequest {

	private static $_parsed_url = false;
	private static $_path = null;
	private static $_query_string = null;

	public function uri() {
		return $_SERVER['REQUEST_URI'];
	}

	public function method() {
		static $result = null;
		if($result === null) {
			$result = strtoupper($_SERVER['REQUEST_METHOD']);
		}
		return $result;
	}

	public function url() {
		static $result = null;
		if($result === null) {
			$result = rawurldecode($this->raw_url());
		}
		return $result;
	}

	public function raw_url() {
		return $_SERVER['REQUEST_URI'];
	}

	public function path() {
		/* TODO Allow slashes to be encoded in path components.
		 * Currently encoded slashes will be treated as component
		 * separators. */
		$this->_parse_url();
		return self::$_path;
	}

	public function query_string() {
		$this->_parse_url();
		return self::$_query_string;
	}

	public function raw_query_string() {
		return $_SERVER['QUERY_STRING'];
	}

	public function form($name = null) {
		static $form = null;
		if($form === null) {
			switch($this->method()) {
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
				parse_str($this->raw_query_string(), $form);
				break;
			default:
				// PUT, PATCH
				parse_str($this->body(), $form);
				break;
			}
		}
		return $name === null ? $form : \phrame\Util::get($form, $name);
	}

	public function header($name = null) {
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
							return \phrame\Util::get($headers, $name);
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

	public function accept() {
		return self::_parse_negotiation($this->header('Accept'));
	}

	public function cookie($name = null) {
		return $name === null ? $_COOKIE : \phrame\Util::get($_COOKIE, $name);
	}

	public function cookies() {
		return $_COOKIE;
	}

	/* Slurp the raw input sent in the request body into a single string.
	 * The result is cached, so calling this function more than once is
	 * fine. */
	public function body() {
		static $result = null;
		if($result === null) {
			$result = file_get_contents('php://input');
		}
		return $result;
	}

	public function file($name = null) {
		return $name === null ? $_FILES : \phrame\Util::get($_FILES, $name);
	}

	public function files() {
		return $_FILES;
	}

	/* Save a file uploaded under the form parameter `$name` to the path
	 * `$dest_path` on the filesystem. Throws `RuntimeException` if the
	 * file is missing, if there is an error code associated with this file
	 * upload, or if it could not be saved. */
	public function save_file($name, $dest_path) {
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

	private function _parse_url() {
		if(!self::$_parsed_url) {
			$parts = parse_url($this->url());
			self::$_path = \phrame\Util::get($parts, 'path');
			self::$_query_string = \phrame\Util::get($parts, 'query');
			self::$_parsed_url = true;
		}
	}

	private static function _header_to_env($name) {
		return strtoupper(str_replace('-', '_', $name));
	}

	private static function _env_to_header($name) {
		return strtolower(str_replace('_', '-', $name));
	}

	private static function _parse_negotiation($str) {
		$result = array();
		$parts = preg_split('/\s*,\s*/', $str);
		foreach($parts as $part) {
			$matches = null;
			if(preg_match('/^(.*?)\s*(;\s*q=(.*))?$/', $part, $matches)) {
				$type = $matches[1];
				if(array_key_exists(2, $matches) && is_numeric($matches[3])) {
					$quality = (float) $matches[3];
				} else {
					$quality = 1.0;
				}
				$result[$type] = $quality;
			}
		}
		arsort($result);
		return $result;
	}
}

?>
