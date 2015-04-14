<?php

namespace phrame\test;

require_once dirname(dirname(__DIR__)) . '/functions/errors.php';

abstract class UnitTest extends Runner {

	private $passed_tests;
	private $failed_tests;
	private $passed_assertions;
	private $failed_assertions;
	private $current_method;
	private $deferred_errors;

	public function run() {
		$this->passed_tests = $this->failed_tests = 0;
		parent::run();
		$msg = (
			$this->passed_tests . '/' .
			($this->passed_tests + $this->failed_tests) .
			' tests passed'
		);
		$r = $this->failed_tests === 0 && $this->passed_tests > 0;
		$msg = $r ? self::green($msg) : self::red($msg);
		echo $msg, "\n";
		return $r;
	}

	public function run_test($method) {
		$this->passed_assertions = $this->failed_assertions = 0;
		$this->current_method = $method;
		$this->deferred_errors = array();
		$e = null;
		try {
			parent::run_test($method);
		} catch(\Exception $e) {
			echo self::red('E');
			++$this->failed_assertions;
		}
		echo "\n";
		$this->current_method = null;
		foreach($this->deferred_errors as $info) {
			echo self::red('assertion failed'), "\n";
			if(isset($info['file'])) {
				echo $info['file'], ':', $info['line'], "\n";
			}
			if(isset($info['message'])) {
				echo $info['message'], "\n";
			}
			if(array_key_exists('expected', $info)) {
				echo self::cyan('---expected---'), "\n";
				var_export($info['actual']);
				echo "\n";
				echo self::cyan('---' . $info['operation'] . '---'), "\n";
				var_export($info['expected']);
				echo "\n";
				echo self::cyan('--------------'), "\n";
			}
		}
		if($e !== null) {
			echo self::red('exception thrown'), "\n";
			\phrame\print_stack_trace($e);
		}
		if($this->failed_assertions > 0 || $this->passed_assertions === 0) {
			echo self::red(
				$method->getName() . ' failed (' .
				$this->passed_assertions . '/' .
				($this->passed_assertions + $this->failed_assertions) .
				' assertions passed)'
			), "\n";
			++$this->failed_tests;
		} else {
			++$this->passed_tests;
		}
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

	private function rec($value, $msg, $i = 1) {
		if($value) {
			++$this->passed_assertions;
			echo self::green('.');
		} else {
			++$this->failed_assertions;
			echo self::red('F');
			$trace = debug_backtrace();
			$this->deferred_errors[] = $trace[$i] + array(
				'file' => null,
				'line' => '???',
				'message' => $msg
			);
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
			$this->add_error_info(array(
				'expected' => $y,
				'operation' => 'to be',
				'actual' => $x
			));
		}
	}

	protected function ne($x, $y, $msg = null) {
		if(!$this->rec($x !== $y, $msg)) {
			$this->add_error_info(array(
				'expected' => $y,
				'operation' => 'not to be',
				'actual' => $x
			));
		}
	}

	protected function lt($x, $y, $msg = null) {
		if(!$this->rec($x < $y, $msg)) {
			$this->add_error_info(array(
				'expected' => $y,
				'operation' => 'to be less than',
				'actual' => $x
			));
		}
	}

	protected function gt($x, $y, $msg = null) {
		if(!$this->rec($x > $y, $msg)) {
			$this->add_error_info(array(
				'expected' => $y,
				'operation' => 'to be greater than',
				'actual' => $x
			));
		}
	}


	private function add_error_info($info) {
		$this->deferred_errors[count($this->deferred_errors) - 1] += $info;
	}
}

?>
