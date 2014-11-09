<?php

call_user_func(function() {
	$base = dirname(dirname(__DIR__));
	$dirs = array();
	foreach(array('lib', 'pages', 'models') as $dir) {
		$dirs[] = "$base/app/$dir";
	}
	foreach(config::modules() as $dir) {
		$dirs[] = "$base/lib/classes/$dir";
	}
	$dirs[] = get_include_path();
	set_include_path(join(PATH_SEPARATOR, $dirs));
});

function __autoload($name) {
	include "$name.php";
}

?>
