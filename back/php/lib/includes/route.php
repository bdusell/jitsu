<?php

call_user_func(function() {
	$url = Request::url();
	$pat = '/^\/?' . preg_quote(trim(config::base_path(), '/'), '/') . '\/([^?]*)/';
	if(preg_match($pat, $url, $matches)) {
		$class = config::router();
		$router = new $class($matches[1]);
		$router->routes();
		$router->route();
	} else {
		call_user_func(array(config::helper(), 'error'), 500, array('path' => $url));
	}
});

?>
