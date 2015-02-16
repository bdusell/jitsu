<?php

namespace phrame\test;

require_once dirname(dirname(__DIR__)) . '/functions/errors.php';

abstract class UnitTest extends Runner {

	private $passed_tests;
	private $failed_tests;
	private $passed_assertions;
	private $failed_assertions;

	public function run() {
		$this->passed_tests = $this->failed_tests = 0;
		parent::run();
		echo $this->passed_tests, '/',
			($this->passed_tests + $this->failed_tests),
			" tests passed\n";
		return $this->failed_tests === 0;
	}

	public function run_test($method) {
		$this->passed_assertions = $this->failed_assertions = 0;
		try {
			parent::run_test($method);
		} catch(Exception $e) {
			echo "\n", $method->getName(), " failed (exception thrown)\n";
			print_stack_trace($e);
			++$this->failed_tests;
		}
		if($this->failed_assertions > 0) {
			echo "\n", $method->getName(), ' failed (', $this->passed_assertions, '/',
				($this->passed_assertions + $this->failed_assertions),
				' assertions passed)';
			++$this->failed_tests;
		} else {
			++$this->passed_tests;
		}
		echo "\n";
	}

	private function rec($value, $msg) {
		if($value) {
			++$this->passed_assertions;
			echo '.';
		} else {
			++$this->failed_assertions;
			echo 'F';
			if($msg !== null) {
				echo "\n  ", $msg, "\n";
			}
		}
		return $value;
	}

	protected function ok($value, $msg = null) {
		$this->rec($value, $msg);
	}

	protected function not($value, $msg = null) {
		$this->rec(!$value, $msg);
	}

	protected function eq($x, $y, $msg = null) {
		if(!$this->rec($x === $y, $msg)) {
			if($msg === null) echo "\n";
			echo "== expected ==\n";
			var_export($y);
			echo "\n== got ==\n";
			var_export($x);
			echo "\n== end ==\n";
		}
	}
}

?>
