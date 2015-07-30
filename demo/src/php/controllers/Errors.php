<?php

namespace DemoApp;

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/jitsu/functions/errors.php';

class Errors {

	public static function not_found($data) {
		Util::wrap($data, function($data) {
			$request = $data->request;
			Util::error($data, 404, array(
				'method' => $request->method(),
				'path' => $request->path()
			));
		});
	}

	public static function bad_method($data) {
		Util::wrap($data, function($data) {
			$request = $data->request;
			Util::error($data, 405, array(
				'method' => $request->method(),
				'path' => $request->path()
			));
		});
	}

	public static function internal_error($data) {
		$request = $data->request;
		$config = $data->config;
		$message = null;
		if($config->show_errors) {
			$exception = $data->exception;
			$message = \jitsu\StringUtil::capture(function() use($exception) {
				\jitsu\print_stack_trace($exception);
			});
		}
		Util::error($data, 500, array(
			'method' => $request->method(),
			'path' => $request->path(),
			'message' => $message
		));
	}
}

?>
