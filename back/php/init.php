<?php
include __DIR__ . '/lib/includes/config.php';
include __DIR__ . '/app/config.php';
if(file_exists('config.php')) {
	include 'config.php';
}
include __DIR__ . '/lib/includes/errors.php';
include __DIR__ . '/lib/includes/path.php';
include __DIR__ . '/lib/includes/route.php';
?>
