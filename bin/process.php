#!/usr/bin/env php
<?php
$name = basename(array_shift($argv));
$usage = function() use($name) {
	echo <<<TXT
Usage: $name [--config config-file] <php-file> ...

Process a file with the phrame configuration and library loaded.

options:
-h --help     Show this help message.
-c --config   Optionally specify an additional config file to load.


TXT
	;
};
$files = array();
$config = null;
while(($arg = array_shift($argv)) !== null) {
	if($arg === '-h' || $arg === '--help') {
		call_user_func($usage);
		exit(0);
	} elseif($arg === '-c' || $arg === '--config') {
		$config = array_shift($argv);
	} else {
		$files[] = $arg;
	}
}
if(!$files) {
	call_user_func($usage);
	exit(1);
}
call_user_func(function() {
	include dirname(__DIR__) . '/src/php/lib/includes/config.php';
	include dirname(__DIR__) . '/src/php/app/config.php';
});
if($config !== null) {
	$__FILE__ = $config;
	call_user_func(function() use ($__FILE__) {
		include $__FILE__;
	});
}
config::show_errors(true);
call_user_func(function() {
	include dirname(__DIR__) . '/src/php/lib/includes/errors.php';
	include dirname(__DIR__) . '/src/php/lib/includes/path.php';
	include dirname(__DIR__) . '/src/php/lib/includes/globals.php';
});
foreach($files as $__FILE__) {
	call_user_func(function() use($__FILE__) {
		include $__FILE__;
	});
}
?>
