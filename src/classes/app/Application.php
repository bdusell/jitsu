<?php

namespace jitsu\app;

/* A jitsu application. */
abstract class Application extends Router {

	public function __construct() {
		parent::__construct();
		$this->handler(new \jitsu\app\handlers\Configure);
		$this->initialize();
	}

	abstract public function initialize();

	public function respond($request, $response, $config) {
		return $this->route((object) array(
			'request' => $request,
			'response' => $response,
			'config' => $config
		));
	}

	public static function main($app, $config) {
		return $app->respond(
			new \jitsu\http\CurrentRequest,
			new \jitsu\http\CurrentResponse,
			$config
		);
	}

	public function callback($callback) {
		$this->handler(new \jitsu\app\handlers\CallbackHandler($callback));
	}

	public function set_namespace($value) {
		$this->handler(new \jitsu\app\handlers\SetNamespace($value));
	}

	public function path($path, $callback) {
		$this->handler(new \jitsu\app\handlers\Path($path, $callback));
	}

	public function endpoint($method, $path, $callback) {
		$this->handler(new \jitsu\app\handlers\Endpoint($method, $path, $callback));
	}

	public function get($path, $callback) {
		$this->endpoint('GET', $path, $callback);
	}

	public function post($path, $callback) {
		$this->endpoint('POST', $path, $callback);
	}

	public function put($path, $callback) {
		$this->endpoint('PUT', $path, $callback);
	}

	public function delete($path, $callback) {
		$this->endpoint('DELETE', $path, $callback);
	}

	public function bad_method($callback) {
		$this->handler(new \jitsu\app\handlers\BadMethod($callback));
	}

	public function not_found($callback) {
		$this->handler(new \jitsu\app\handlers\Always($callback));
	}

	public function error($callback) {
		$this->error_handler(new \jitsu\app\handlers\Always($callback));
	}
}

?>
