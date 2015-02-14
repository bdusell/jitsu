<?php

require_once dirname(dirname(dirname(__DIR__))) . '/vendor/phrame/functions/errors.php';

use phrame\StringUtil;
use phrame\RequestUtil;

class ErrorController {

	public static function not_found() {
		$request = RequestUtil::instance();
		Pages::error(404, array(
			'method' => $request->method(),
			'path' => $request->path()
		));
	}

	public static function internal_error($exception) {
		$request = RequestUtil::instance();
		$config = AppConfig::instance();
		$message = null;
		if($config->show_errors) {
			$message = StringUtil::capture(function() use($exception) {
				\phrame\print_stack_trace($exception);
			});
		}
		Pages::error(500, array(
			'method' => $request->method(),
			'path' => $request->path(),
			'message' => $message
		));
	}

	public static function bad_method($methods) {
		$request = RequestUtil::instance();
		Pages::error(405, array(
			'method' => $request->method(),
			'path' => $request->path()
		));
	}

	public static function permanent_redirect($path) {
		Pages::redirect($path, 301);
		exit(0);
	}
}

?>
