<?php
$project_dir = dirname(dirname(__DIR__));
require_once $project_dir . '/vendor/phrame/functions/errors.php';
phrame\set_error_visibility(true);
require $project_dir . '/vendor/phrame/autoload.php';
require $project_dir . '/src/php/main.php';
?>
