<?php
include __DIR__ . '/includes/config.php';
include dirname(dirname(__DIR__)) . '/src/app/config.php';
call_user_func(function() {
	if(file_exists($filename = getcwd() . '/config.php')) {
		include $filename;
	}
});
include __DIR__ . '/includes/errors.php';
include __DIR__ . '/includes/path.php';
include __DIR__ . '/includes/globals.php';
include __DIR__ . '/includes/route.php';
?>
