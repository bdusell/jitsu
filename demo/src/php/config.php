<?php
$config->locale = array('en_US.utf8', 'C.UTF-8', 'C', 'POSIX');
$config->site_name = 'Example Website';
$config->sqlite_file = dirname(dirname(__DIR__)) . '/db/database.db';
$config->db_host = 'localhost';
$config->db_name = 'phrame';
$config->db_user = 'phrame_user';
$config->db_password = 'phrame_password';
$config->persistent_db_connections = true;
?>
