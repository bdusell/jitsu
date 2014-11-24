<?php

/* Escape a string for interpolation in HTML. */
function html($str) {
	return htmlspecialchars($str);
}

/* Escape a string for interpolation inside a double-quoted HTML attribute. */
function htmlattr($str) {
	return htmlspecialchars($str, ENT_QUOTES);
}

/* Return a PHP-code representation of a value as a string. */
function repr($x) {
	return var_export($x, true);
}

?>
