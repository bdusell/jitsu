<?php

call_user_func(function() {
	$app_base = dirname(dirname(dirname(__DIR__))) . '/src/app';
	$dirs = array();
	foreach(array('helpers', 'models', 'views', 'controllers') as $dir) {
		$dirs[] = "$app_base/$dir";
	}
	$dirs[] = dirname(__DIR__) . '/classes';
	$dirs[] = get_include_path();
	set_include_path(join(PATH_SEPARATOR, $dirs));
});

function __autoload($name) {
	include "$name.php";
}

?>
