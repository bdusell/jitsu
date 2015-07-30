<?php
require_once dirname(dirname(__DIR__)) . '/vendor/jitsu/autoload.php';
require_once dirname(dirname(__DIR__)) . '/vendor/jitsu/functions/common.php';
require_once __DIR__ . '/autoload.php';
\jitsu\app\Application::main(
	new \DemoApp\Application,
	\DemoApp\Application::config()
);
?>
