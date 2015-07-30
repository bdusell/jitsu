<?php
$config->locale = array('en_US.utf8', 'C.UTF-8', 'C', 'POSIX');
$config->site_name = 'Example Website';
$config->sqlite_file = dirname(dirname(__DIR__)) . '/db/database.db';
$config->database_host = 'localhost';
$config->database_name = 'demo_database';
$config->database_user = 'demo_user';
$config->database_password = 'demo_password';
$config->persistent_database_connections = true;
?>
