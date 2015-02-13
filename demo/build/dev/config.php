<?php
$config->scheme = 'http';
$config->host = \phrame\RequestUtil::header('Host');
$config->path = '/phrame-dev/';
$config->document_root = '/var/www/phrame-dev';
?>
