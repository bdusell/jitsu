<?php

/* Include this script to register the phrame class autoloader. */

spl_autoload_register(function($class) {

	/* Auto-load classes under the `phrame` namespace. */
	$prefix = 'phrame\\';
	$base_dir = __DIR__ . '/classes/';

	/* Is this a phrame class? */
	$len = strlen($prefix);
	if(strncmp($class, $prefix, $len) !== 0) {
		return;
	}
	$relative_class = substr($class, $len);

	/* Try loading the corresponding PHP file. */
	$filename = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
	if(file_exists($filename)) {
		require $filename;
	}
});

?>
