<?php
$config->scheme = 'http';
$config->host = \jitsu\Request::header('Host');
$config->path = '/jitsu-dev/';
$config->document_root = '/var/www/jitsu-dev';
$config->sql_driver = 'sqlite';
$config->output_buffering = false;
$config->show_errors = true;
$config->show_server = true;
$config->minify_json = false;
?>
