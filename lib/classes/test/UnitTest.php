<?php

namespace phrame\test;

require_once dirname(dirname(__DIR__)) . '/functions/errors.php';

abstract class UnitTest extends Runner {

	private $passed_tests;
	private $failed_tests;
	private $passed_assertions;
	private $failed_assertions;
	private $current_method;

	public function run() {
		$this->passed_tests = $this->failed_tests = 0;
		parent::run();
		$msg = (
			$this->passed_tests . '/' .
			($this->passed_tests + $this->failed_tests) .
			" tests passed"
		);
		$r = $this->failed_tests === 0;
		$msg = $r ? self::green($msg) : self::red($msg);
		echo "\n", $msg, "\n";
		return $r;
	}

	public function run_test($method) {
		$this->passed_assertions = $this->failed_assertions = 0;
		$this->current_method = $method;
		try {
			parent::run_test($method);
		} catch(Exception $e) {
			echo "\n", self::red(
				$method->getName() .
				" failed (exception thrown)"
			), "\n";
			print_stack_trace($e);
			++$this->failed_tests;
		}
		$this->current_method = null;
		if($this->failed_assertions > 0) {
			echo self::red(
				$method->getFileName() . ':' .
				$method->getStartLine() . ': ' .
				$method->getName() . ' failed (' .
				$this->passed_assertions . '/' .
				($this->passed_assertions + $this->failed_assertions) .
				' assertions passed)'
			);
			++$this->failed_tests;
		} else {
			++$this->passed_tests;
		}
		echo "\n";
	}

	private static function red($s) {
		return "\033[31m$s\033[0m";
	}

	private static function green($s) {
		return "\033[32m$s\033[0m";
	}

	private static function cyan($s) {
		return "\033[36m$s\033[0m";
	}

	private function rec($value, $msg) {
		if($value) {
			++$this->passed_assertions;
			echo self::green('.');
		} else {
			++$this->failed_assertions;
			echo self::red("F"), "\n";
			if($msg !== null) {
				echo $msg, "\n";
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
			echo self::cyan("---expected---"), "\n";
			var_export($y);
			echo "\n", self::cyan("-----got------"), "\n";
			var_export($x);
			echo "\n", self::cyan("--------------"), "\n";
		}
	}
}

?>
