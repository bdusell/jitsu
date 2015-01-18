<?php
config::set('site_name', 'Example Website');
config::locale('en_US.utf8', 'C.UTF-8', 'C', 'POSIX');
config::set('sql_driver', 'sqlite');
config::set('sqlite_file', dirname(dirname(__DIR__)) . '/db/database.db');
config::set('db_host', 'localhost');
config::set('db_name', 'phrame');
config::set('db_user', 'phrame_user');
config::set('db_password', 'phrame_password');
?>
