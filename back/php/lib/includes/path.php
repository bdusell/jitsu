<?php

call_user_func(function() {
	$base = dirname(dirname(__DIR__));
	$dirs = array();
	foreach(array('lib', 'models', 'views', 'controllers') as $dir) {
		$dirs[] = "$base/app/$dir";
	}
	$dirs[] = "$base/lib/classes";
	$dirs[] = get_include_path();
	set_include_path(join(PATH_SEPARATOR, $dirs));
});

function __autoload($name) {
	include "$name.php";
}

?>
