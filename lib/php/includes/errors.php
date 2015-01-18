<?php

/* Display errors at all severity levels (redundant, since the default error
 * handler is overridden anyway). */
error_reporting(E_ALL);

/* Display errors if not in production (redundant, since default error handler
 * is overridden). */
ini_set('display_errors', config::show_errors() ? 1 : 0);

/* Display startup errors which cannot be handled by the normal error
 * handler. */
ini_set('display_startup_errors', config::show_errors() ? 0 : 1);

/* Log errors to the server's logs in production. */
ini_set('log_errors', config::is_production() ? 1 : 0);

/* Ignore repeated errors when logging errors in production. */
if(config::is_production()) {
	ini_set('ignore_repeated_errors', 1);
}

/* Report detected memory leaks if not in production. */
ini_set('report_memleaks', config::is_production() ? 0 : 1);

/* Never format errors in HTML (redundant, since the error handler is
 * overridden anyway). */
ini_set('html_errors', 0);

/* Hide the X-Powered-By header in production. */
if(config::is_production()) {
	header_remove('X-Powered-By');
}

/* Convert all errors to exceptions. */
set_error_handler(function($code, $msg, $file, $line) {
	/* Do NOT throw an exception if the `@` operator was used. */
	if(error_reporting()) {
		throw new ErrorException($msg, 0, $code, $file, $line);
	}
});

function print_stack_trace($e) {
	echo (
		get_class($e) . ': ' . $e->getMessage() .
		' [' . $e->getCode() . "]\n"
	);
	foreach($e->getTrace() as $level) {
		$level += array(
			'class' => '',
			'type' => '',
			'function' => '???',
			'file' => '',
			'line' => ''
		);
		extract($level);
		if($file !== '') {
			echo (
				'  ' . str_pad($class . $type . $function, 15) .
				' at ' . $file . ':' . $line
			);
		} else {
			echo (
				'  ' . $class . $type . $function
			);
		}
		echo "\n";
	}
}

/* Override the default exception handler. */
if(config::show_errors()) {
	set_exception_handler('print_stack_trace');
} else {
	/* Silence everything. */
	set_exception_handler(function($e) {});
}

?>
