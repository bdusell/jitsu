<?php

namespace jitsu\test;

abstract class Profiler extends Runner {

	private $iterations;

	public function __construct($iterations) {
		$this->iterations = $iterations;
	}

	public function run() {
		echo $this->iterations, " iterations\n";
		parent::run();
		echo "done\n";
	}

	protected function run_test($method) {
		echo $method->getName(), ': ';
		$start = self::now();
		for($i = 0; $i < $this->iterations; ++$i) {
			parent::run_test($method);
		}
		$duration = self::now() - $start;
		echo ($duration / 1000.0), " seconds\n";
	}

	private static function now() {
		return microtime();
	}
}

?>
