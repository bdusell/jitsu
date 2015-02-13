#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/cli.php';

$name = basename(array_shift($argv));
$usage = function() use($name) {
	echo <<<TXT
Usage: $name <php-file> ...

Process a set of files with the phrame library pre-loaded.

TXT
	;
};

$files = array();
while(($arg = array_shift($argv)) !== null) {
	if($arg === '-h' || $arg === '--help') {
		call_user_func($usage);
		exit(0);
	} else {
		$files[] = $arg;
	}
}
if(!$files) {
	call_user_func($usage);
	exit(1);
}

foreach($files as $__FILE__) {
	call_user_func(function() use($__FILE__) {
		include $__FILE__;
	});
}
?>
