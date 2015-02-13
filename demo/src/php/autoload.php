<?php

use phrame\MetaUtil;

MetaUtil::register_autoloader(function($class) {
	$dirs = array('models', 'controllers', 'helpers');
	foreach($dirs as $dir) {
		$filename = __DIR__ . '/' . $dir . '/' . $class . '.php';
		if(file_exists($filename)) {
			require $filename;
		}
	}
});

?>
