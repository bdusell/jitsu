<?php

namespace jitsu\http;

use \jitsu\Response;

class CurrentResponse extends AbstractResponse {

	public function status($version, $code, $reason) {
		return Response::status($version, $code, $reason);
	}

	/* Note that this is mutually exclusive with `status()`. */
	public function code($code = null) {
		return Response::code($code);
	}

	public function header($name, $value) {
		return Response::header($name, $value);
	}

	public function cookie($name, $value,
		$lifespan = null, $domain = null, $path = null)
	{
		return Response::cookie($name, $value, $lifespan, $domain, $path);
	}

	public function delete_cookie($name,
		$domain = null, $path = null)
	{
		return Response::delete_cookie($name, $domain, $path);
	}

	public function redirect($url, $code) {
		return Response::redirect($url, $code);
	}

	public function start_output_buffering() {
		return Response::start_output_buffering();
	}

	public function flush_output_buffer() {
		return Response::flush_output_buffer();
	}

	public function clear_output_buffer() {
		return Response::clear_output_buffer();
	}

	public function json($obj, $pretty = false) {
		return Response::json($obj, $pretty);
	}

	public function file($path, $content_type) {
		return Response::file($path, $content_type);
	}
}

?>
