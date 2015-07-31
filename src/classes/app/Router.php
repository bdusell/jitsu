<?php

namespace jitsu\app;

class Router {

	private $handlers = array();
	private $error_handlers = array();

	public function __construct() {
	}

	public function handler($handler) {
		$this->handlers[] = $handler;
	}

	public function error_handler($handler) {
		$this->error_handlers[] = $handler;
	}

	public function route($data) {
		foreach($this->handlers as $handler) {
			try {
				if($handler->handle($data)) {
					return true;
				}
			} catch(\Exception $e) {
				$data->exception = $e;
				foreach($this->error_handlers as $error_handler) {
					if($error_handler->handle($data)) {
						return true;
					}
				}
				unset($data->exception);
			}
		}
		return false;
	}
}

?>