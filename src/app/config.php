<?php
config::set('site_name', 'Example Website');
config::locale('en_US.utf8', 'C.UTF-8', 'C', 'POSIX');
config::set('sql_driver', 'sqlite');
config::set('sql_database', dirname(dirname(__DIR__)) . '/db/database.db');
?>
