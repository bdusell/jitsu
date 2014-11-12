<?php
include __DIR__ . '/lib/includes/config.php';
include __DIR__ . '/app/config.php';
call_user_func(function() {
	if(file_exists($filename = getcwd() . '/config.php')) {
		include $filename;
	}
});
include __DIR__ . '/lib/includes/errors.php';
include __DIR__ . '/lib/includes/path.php';
include __DIR__ . '/lib/includes/globals.php';
include __DIR__ . '/lib/includes/route.php';
?>
