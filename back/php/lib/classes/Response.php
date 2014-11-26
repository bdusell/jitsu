<?php

/* Utilities for building the response sent back to the client. */
class Response {

	/* Set the HTTP response code of the response. If `$code` is null,
	 * return the currently set response code. */
	public static function code($code = null) {
		if($code === null) return http_response_code();
		else return http_response_code($code);
	}

	/* Set a header in the response. Overwrites any previous header with
	 * the same name. Must be called before output is written, just like
	 * PHP `header`. */
	public static function header($name, $value) {
		header("$name: $value");
	}

	/* Set the content type of the response using the `Content-Type`
	 * header. */
	public static function content_type($type) {
		self::header('Content-Type', $type);
	}

	/* Send a cookie back to the client. Provide a lifespan in seconds from
	 * the current time; if this is null, the cookie has an unlimited
	 * lifespan. Optionally provide a domain and path for the cookie. */
	public static function cookie($name, $value, $lifespan = null, $domain = null, $path = null) {
		setcookie(
			$name,
			$value,
			$lifespan === null ? 0 : time() + $lifespan,
			$path,
			$domain
		);
	}

	/* Request that a cookie be deleted from the client. */
	public static function delete_cookie($name, $domain = null, $path = null) {
		setcookie($name, '', 1, $path, $domain);
	}

	/* Shorthand for sending a PHP array as a JSON object in the
	 * response. */
	public static function json($obj, $pretty = true) {
		self::content_type('application/json');
		echo Serialize::json($obj, $pretty);
	}

	/* Shorthand for sending a file with a given content type in the
	 * response. */
	public static function file($path, $content_type) {
		self::content_type($content_type);
		readfile($path);
	}

	/* Issue a redirect to another URL. Note that this does NOT exit the
	 * current process. Keep in mind that relying on the client to respect
	 * this header and close the connection for you is potentially a huge
	 * security hole.
	 *
	 * The response code is also set here. The response code should be a
	 * 3XX code. */
	public static function redirect($url, $code) {
		header('Location: ' . $url, true, $code);
	}
}

?>
