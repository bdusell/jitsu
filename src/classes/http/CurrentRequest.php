<?php

namespace jitsu\http;

use \jitsu\Request;

/* Get information about the current request being processed. */
class CurrentRequest extends AbstractRequest {

	public function scheme() {
		return Request::scheme();
	}

	public function protocol() {
		return Request::protocol();
	}

	public function host() {
		return Request::host();
	}

	public function method() {
		return Request::method();
	}

	public function uri() {
		return Request::uri();
	}

	public function path() {
		return Request::path();
	}

	public function query_string() {
		return Request::query_string();
	}

	public function form($name = null) {
		return Request::form($name);
	}

	public function header($name = null) {
		return Request::header($name);
	}

	public function cookie($name = null) {
		return Request::cookie($name);
	}

	public function cookies() {
		return Request::cookies();
	}

	public function body() {
		return Request::body();
	}

	public function file($name = null) {
		return Request::file($name);
	}

	public function files() {
		return Request::files();
	}

	public function save_file($name, $dest_path) {
		return Request::save_file($name, $dest_path);
	}

	public function origin_ip_address() {
		return Request::origin_ip_address();
	}

	public function origin_port() {
		return Request::origin_port();
	}

	public function timestamp() {
		return Request::timestamp();
	}
}

?>
