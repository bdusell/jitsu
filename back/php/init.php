<?php

/* Normalize the base directory. */
$BASE_DIR = trim($BASE_DIR, '/');
if($BASE_DIR !== '') $BASE_DIR .= '/';
$DOCUMENT_ROOT = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/';

/* Update the include path. */
$PATH[] = 'app/lib';
$PATH[] = 'app/pages';
$PATH[] = 'app/models';
foreach($MODULES as $p) $PATH[] = "lib/$p";
$absolute_paths = array();
foreach($PATH as $p) $absolute_paths[] = "$DOCUMENT_ROOT$BASE_DIR$SOURCE_DIR$p";
$absolute_paths[] = get_include_path();
set_include_path(join(PATH_SEPARATOR, $absolute_paths));

/* Set up class autoloading. */
function __autoload($name) {
	include "$name.php";
}

/* Apply error reporting settings. */
ini_set('display_errors',         $DEBUG ? 1 : 0);
ini_set('display_startup_errors', $DEBUG ? 1 : 0);
ini_set('html_errors',            0);
error_reporting($DEBUG ? E_ALL : 0);

/* Override the default error handler. */
function phrame_error_handler($errno, $errstr, $errfile, $errline /*, $errcontext*/) {
	global $DEBUG;
	if($DEBUG) { ?>
<pre>Error in <?= htmlspecialchars($errfile) ?>:<?= $errline ?> [<?= $errno ?>]:
<?= htmlspecialchars($errstr) ?></pre>
<?php
	}
}
set_error_handler('phrame_error_handler');

/* Override the default exception handler. */
function phrame_exception_handler($e) {
	global $DEBUG;
	if($DEBUG) { ?>
<pre>Exception in <?= htmlspecialchars($e->getFile()) ?>:<?= $e->getLine() ?> [<?= $e->getCode() ?>]:
<?= htmlspecialchars($e->getMessage()) ?>


Stack trace:
<?= htmlspecialchars(Util::format_stack_trace($e->getTrace())) ?></pre>
<?php
	}
}
set_exception_handler('phrame_exception_handler');

?>
