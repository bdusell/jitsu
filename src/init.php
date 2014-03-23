<?php

/* Set to false on the live site. */
$DEBUG = true;

$BASE = '';

/* Set the include path. */
set_include_path(
	get_include_path() . PATH_SEPARATOR .
	$_SERVER['DOCUMENT_ROOT'] . "$BASE/classes" . PATH_SEPARATOR .
	$_SERVER['DOCUMENT_ROOT'] . "$BASE/views" . PATH_SEPARATOR .
	$_SERVER['DOCUMENT_ROOT'] . $BASE
);

/* Set up class autoloading. */
function __autoload($name) {
	include "$name.php";
}

Config::set_base($BASE);

/* Activate error reporting. */
ini_set('display_errors', $DEBUG ? 1 : 0);
ini_set('display_startup_errors', $DEBUG ? 1 : 0);
ini_set('html_errors', 0);
error_reporting($DEBUG ? E_ALL : 0);

/* Register a custom error handler which throws an exception. */
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

/* Register a custom exception handling routine. */
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
AccessLog::log();

?>
