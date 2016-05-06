<?php use Jitsu\Util; ?>
<?= '<?php' ?>

$config->show_stack_traces = false;
$config->output_buffering = true;
$config->host = 'localhost';
$config->path = '/';
$config->document_root = <?= Util::repr("/var/www/$package_name/prod") ?>;
