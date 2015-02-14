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
			if($this->config->buffer_output) {
				$response->start_buffer();
			}
			$noexcept = false;
			try {
				$this->router->route(
					$response,
					$request->method(),
					$route
				);
				$noexcept = true;
			} catch(Exception $e) {
				if($this->config->buffer_output) {
					$response->clear_buffer();
				}
				// TODO handle internal error
				$this->router->call(
					'internal_error',
					$e,
					$this->method,
					$this->path
				);
			}
			if($noexcept) {
				if($this->config->buffer_output) {
					$response->flush_buffer();
				}
			}
		} else {
			throw new ConfigurationError(
				'misconfigured base path; expected ' .
				$path . ' to start with ' . $config->base_path
			);
		}
	}
}

?>
