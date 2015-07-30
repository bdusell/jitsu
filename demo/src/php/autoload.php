<?php

use jitsu\MetaUtil;

MetaUtil::autoload_namespace('DemoApp', array(
	__DIR__,
	__DIR__ . '/models',
	__DIR__ . '/controllers',
	__DIR__ . '/helpers'
));

?>
