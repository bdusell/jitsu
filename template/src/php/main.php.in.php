<?= '<?php' ?>

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/functions.php';
<?= $namespace ?>\Application::main(
	new \Jitsu\App\SiteConfig(__DIR__ . '/config.php', './config.php'));
