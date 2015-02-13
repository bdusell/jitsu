<?php

namespace phrame\http;

/* Utilities for building the current response about to be sent back to the
 * client. */
class CurrentResponse extends AbstractResponse {

	public function code($code = null) {
		if($code === null) return http_response_code();
		else return http_response_code($code);
	}

	/* Set a header in the response. Overwrites any previous header with
	 * the same name. Must be called before output is written, just like
	 * PHP `header`. */
	public function header($name, $value) {
		header("$name: $value");
	}

	public function cookie($name, $value,
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

	public function delete_cookie($name,
		$domain = null, $path = null)
	{
		setcookie($name, '', 1, $path, $domain);
	}

	public function redirect($url, $code) {
		header('Location: ' . $url, true, $code);
	}

	public function start_buffer() {
		ob_start();
	}

	public function flush_buffer() {
		ob_end_flush();
	}

	public function clear_buffer() {
		ob_end_clean();
	}

	/* Shorthand for sending a PHP array as a JSON object in the
	 * response. */
	public function json($obj, $pretty = false) {
		$this->content_type('application/json');
		echo JSONUtil::encode($obj, $pretty);
	}

	/* Shorthand for sending a file with a given content type in the
	 * response. */
	public function file($path, $content_type) {
		$this->content_type($content_type);
		readfile($path);
	}
}

?>
