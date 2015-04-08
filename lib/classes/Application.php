<?php

namespace phrame;

require_once dirname(__DIR__) . '/functions/errors.php';

/* A phrame application. */
class Application {

	private $router;
	private $config;
	private $navigator;

	public function __construct($router, $config) {
		$this->router = $router;
		$this->config = $config;
	}

	/* Respond to the HTTP request. */
	public function respond($request, $response) {
		$path = $request->path();
		$route = $this->config->remove_path($path);
		if($route !== null) {
			$this->router->route(
				$response,
				$request->method(),
				$route
			);
		} else {
			throw new \LogicException(
				'misconfigured base path; expected ' .
				$path . ' to start with ' . $this->config->base_path
			);
		}
	}
}

?>
