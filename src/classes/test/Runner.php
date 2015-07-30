<?php

namespace jitsu\test;

abstract class Runner {

	public function run() {
		$r = new \ReflectionObject($this);
		$public_methods = $r->getMethods(\ReflectionMethod::IS_PUBLIC);
		foreach($public_methods as $method) {
			if(strncmp($method->getName(), 'test', 4) === 0) {
				$this->run_test($method);
			}
		}
	}

	protected function run_test($method) {
		$method->invoke($this);
	}
}

?>
