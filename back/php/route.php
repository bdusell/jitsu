<?php

$url = Request::url();
$pat = '/^\/?' . preg_quote($BASE_DIR, '/') . '(.*)$/';
if(preg_match($pat, $url, $matches)) {
	$r = new AppRouter($matches[1]);
	$r->routes();
	$r->route();
} else {
	Util::template($SOURCE_DIR . 'app/views/404.php');
}

?>
