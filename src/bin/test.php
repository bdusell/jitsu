#!/usr/bin/env php
<?php

require dirname(__DIR__) . '/cli.php';

$name = basename(array_shift($argv));
$usage = function() use($name) {
	echo <<<TXT
Usage: $name <unit-test-file>

Execute the class definition in a file as a unit test.

TXT
	;
};

$test_file = array_shift($argv);
if($test_file === null) {
	call_user_func($usage);
	exit(1);
}
array_unshift($argv, $test_file);

$classes1 = get_declared_classes();
$__FILE__ = $test_file;
call_user_func(function() use($__FILE__) {
	include $__FILE__;
});
$classes2 = get_declared_classes();
$new_classes = array_diff($classes2, $classes1);
if(!$new_classes) {
	echo 'error: ', $test_file, " did not define a class\n";
	exit(1);
}

$test_class = array_pop($new_classes);
$test = new $test_class();
$r = $test->run();
if(!$r) {
	exit(1);
}

?>
