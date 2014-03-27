<?php

/* Load essential settings. */
include 'config.php';

$BASE_DIR = trim($BASE_DIR, '/');

/* Update the include path to include classes and view classes. */
set_include_path(
	$_SERVER['DOCUMENT_ROOT'] . "/$BASE_DIR/classes" . PATH_SEPARATOR .
	$_SERVER['DOCUMENT_ROOT'] . "/$BASE_DIR/views" . PATH_SEPARATOR .
	$_SERVER['DOCUMENT_ROOT'] . "/$BASE_DIR" . PATH_SEPARATOR
	get_include_path()
);

/* Set up class autoloading. */
function __autoload($name) {
	include "$name.php";
}

/* Apply error reporting settings. */
ini_set('display_errors', $DEBUG ? 1 : 0);
ini_set('display_startup_errors', $DEBUG ? 1 : 0);
ini_set('html_errors', 0);
error_reporting($DEBUG ? E_ALL : 0);

/* Override the default error handler. */
function my_error_handler($errno, $errstr, $errfile, $errline /*, $errcontext*/) {
	global $DEBUG;
	if($DEBUG) {
?>
<pre>Error in <?= htmlspecialchars($errfile) ?>:<?= $errline ?> [<?= $errno ?>]:
<?= htmlspecialchars($errstr) ?></pre>
<?php
	}
}
set_error_handler('my_error_handler');

/* Override the default exception handler. */
function my_exception_handler($e) {
	global $DEBUG;
	if($DEBUG) {
?>
<pre>Exception in <?= htmlspecialchars($e->getFile()) ?>:<?= $e->getLine() ?> [<?= $e->getCode() ?>]:
<?= htmlspecialchars($e->getMessage()) ?>


Stack trace:
<?= htmlspecialchars(Util::format_stack_trace($e->getTrace())) ?></pre>
<?php
	}
}
set_exception_handler('my_exception_handler');

/* Log this request. */
if($ACCESS_LOGGING) AccessLog::log();

?>
