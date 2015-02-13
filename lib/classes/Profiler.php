<?php

/* Usage:
 * class MyProfiler extends Profiler {
 *     public function testThis() {
 *         // run some test
 *     }
 *     public function testThat() {
 *         // run some other test
 *     }
 * }
 * // Run the two tests 1000 times and display the execution time on stdout
 * Profiler::run();
 * */

namespace phrame;

abstract class Profiler {

	private $start_time;

	private function before_all($iterations) {
		echo $iterations, " iterations\n";
	}

	private function before_each($method) {
		$this->start_time = $this->now();
	}

	private function after_each($method) {
		$duration = $this->now() - $this->start_time;
		echo $method->getName(), ': ', $duration, " microseconds\n";
	}

	private function after_all() {
		echo "done\n";
	}

	private function now() {
		return microtime();
	}

	private function filter_method($method) {
		return substr($method->getName(), 0, 4) === 'test';
	}

	public static function run($iterations = 1000) {
		$class = get_called_class();
		$instance = new $class();
		$instance->before_all($iterations);
		$object = new \ReflectionObject($instance);
		foreach($object->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
			if($this->filter_method($method)) {
				$instance->start($method);
				for($i = 0; $i < $iterations; ++$i) {
					$method->invoke($this);
				}
				$instance->stop($method);
			}
		}
		$instance->end();
	}
}

?>
