<?php use Jitsu\Util; ?>
<?= '<?php' ?>

$config->locale = ['en_US.utf8', 'C.UTF-8', 'C', 'POSIX'];
$config->scheme = 'http';
$config->database = [
	'driver' => 'mysql',
	'host' => 'localhost',
	'database' => <?= Util::repr($database_name) ?>,
	'user' => <?= Util::repr($database_name . '_user') ?>,
	'password' => 'password',
	'persistent' => true
];
