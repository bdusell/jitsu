<?php
$project_dir = dirname(dirname(__DIR__));
require_once $project_dir . '/vendor/jitsu/functions/errors.php';
\jitsu\set_error_visibility(false);
header_remove('X-Powered-By');
require $project_dir . '/src/php/main.php';
?>
