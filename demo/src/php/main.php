<?php

use phrame\RequestUtil;
use phrame\ResponseUtil;

require_once dirname(dirname(__DIR__)) . '/vendor/phrame/functions/common.php';
require_once dirname(dirname(__DIR__)) . '/vendor/phrame/functions/errors.php';
require __DIR__ . '/autoload.php';

$request = RequestUtil::instance();
$response = ResponseUtil::instance();
$config = AppConfig::instance();
if($config->buffer_output) {
	$response->start_buffer();
}
try {
	App::respond($request, $response);
	if($config->buffer_output) {
		$response->flush_buffer();
	}
} catch(Exception $e) {
	if($config->buffer_output) {
		$response->clear_buffer();
		ErrorController::internal_error($e);
	} else {
		if(!headers_sent()) {
			$response->code(500);
		}
		if($config->show_errors) {
			if(headers_sent()) {
				echo html(StringUtil::capture(function() use($e) {
					\phrame\print_stack_trace($e);
				}));
			} else {
				$response->content_type('text/plain');
				\phrame\print_stack_trace($e);
			}
		}
	}
}

?>
