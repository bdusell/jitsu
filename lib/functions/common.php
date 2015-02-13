<?php

/* Escape a string for interpolation in HTML text. Note that this does NOT
 * encode double quotes (`"`). */
function html($str) {
	return htmlspecialchars($str, ENT_NOQUOTES | ENT_HTML5, 'UTF-8');
}

/* Escape a string for interpolation inside a double-quoted HTML attribute. */
function htmlattr($str) {
	return htmlspecialchars($str, ENT_COMPAT | ENT_HTML5, 'UTF-8');
}

/* Return a PHP-code representation of a value as a string. */
function repr($x) {
	return var_export($x, true);
}

/* Wrap a string in an `XString` object. */
function xstring($s) {
	return new \phrame\XString($s);
}

/* Wrap an array in an `XArray` object. */
function xarray($a) {
	return new \phrame\XArray($a);
}

/* Create an `XRegex` object. */
function xregex(/* $pat [, $flags [, $start [, $end ]]] */) {
	return \phrame\MetaUtil::apply_constructor('\\phrame\\XRegex', func_get_args());
}

?>
