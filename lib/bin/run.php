#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/cli.php';

$name = basename(array_shift($argv));
$usage = function() use($name) {
	echo <<<TXT
Usage: $name -i <init-file> ... <php-file> [arg1 arg2 ...]

Process a set of files with the phrame library pre-loaded.

Arguments:
  <php-file>  Main PHP file to run.
  arg1 ...    Arguments passed to main PHP script as \$argv.

Options:
  -i --init   PHP file to execute prior to running the main script.
  -h --help   This help message.

TXT
	;
};

$init_files = array();
$main_file = null;
while(($arg = array_shift($argv)) !== null) {
	if($arg === '-h' || $arg === '--help') {
		call_user_func($usage);
		exit(0);
	} elseif($arg === '-i' || $arg === '--init') {
		$arg = array_shift($argv);
		if($arg === null) {
			call_user_func($usage);
			exit(1);
		}
		$init_files[] = $arg;
	} else {
		$main_file = $arg;
		break;
	}
}
if($main_file === null) {
	call_user_func($usage);
	exit(1);
}
array_unshift($argv, $main_file);

$__INIT__ = $init_files;
$__MAIN__ = $main_file;
call_user_func(function() use($__INIT__, $__MAIN__, &$argv) {
	while($__INIT__) {
		include $__INIT__[0];
		array_shift($__INIT__);
	}
	include $__MAIN__;
});

?>
