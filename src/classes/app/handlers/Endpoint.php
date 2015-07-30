<?php

namespace jitsu\app\handlers;

class Endpoint extends Path {

	private $method;

	public function __construct($method, $path, $callback) {
		parent::__construct($path, $callback);
		$this->method = strtoupper($method);
	}

	public function matches($data) {
		if(parent::matches($data)) {
			if($data->request->method() === $this->method) {
				return true;
			} else {
				$data->available_methods[] = $this->method;
			}
		}
		return false;
	}
}

?>
