<?php use Jitsu\Util; ?>
<?= '<?php' ?>

$config->show_stack_traces = true;
$config->output_buffering = false;
$config->host = @$_SERVER['HTTP_HOST'];
$config->path = <?= Util::repr("/$package_name/") ?>;
$config->document_root = <?= Util::repr("/var/www/$package_name/dev") ?>;
