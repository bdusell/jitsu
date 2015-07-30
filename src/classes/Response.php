<?php

namespace jitsu;

/* Utilities for building the current response about to be sent back to the
 * client. */
class Response {

	/* Note that this is mutually exclusive with `code()`. */
	public static function status($version, $code, $reason) {
		header("$version $code $reason");
	}

	/* Note that this is mutually exclusive with `status()`. */
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

	public static function cookie($name, $value,
		$lifespan = null, $domain = null, $path = null)
	{
		setcookie(
			$name,
			$value,
			$lifespan === null ? 0 : time() + $lifespan,
			$path,
			$domain
		);
	}

	public static function delete_cookie($name,
		$domain = null, $path = null)
	{
		setcookie($name, '', 1, $path, $domain);
	}

	public static function redirect($url, $code) {
		header('Location: ' . $url, true, $code);
	}

	public static function start_output_buffering() {
		ob_start();
	}

	public static function flush_output_buffer() {
		ob_end_flush();
	}

	public static function clear_output_buffer() {
		ob_end_clean();
	}

	/* Shorthand for sending a PHP array as a JSON object in the
	 * response. */
	public static function json($obj, $pretty = false) {
		self::content_type('application/json');
		echo JSONUtil::encode($obj, $pretty);
	}

	/* Shorthand for sending a file with a given content type in the
	 * response. */
	public static function file($path, $content_type) {
		self::content_type($content_type);
		readfile($path);
	}
}

?>
