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
		$response = ResponseUtil::instance();
		$config = AppConfig::instance();
		if(true || $config->buffer_output) {
			$response->clear_buffer();
			Pages::error(500, array(
				'message' => StringUtil::capture(function() use($exception) {
					\phrame\print_stack_trace($exception);
				})
			));
		} else {
			if(!headers_sent()) {
				$response->code(500);
				$response->content_type('text/plain');
			}
			\phrame\print_stack_trace($exception);
		}
	}

	public static function bad_method($methods) {
		// TODO
	}
}

?>
