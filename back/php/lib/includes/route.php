<?php

class router {

	private static $router;

	public static function set($router) {
		self::$router = $router;
	}

	public static function __callStatic($name, $args) {
		return call_user_func_array(array(self::$router, $name), $args);
	}
}

call_user_func(function() {
	$url = Request::path();
	// TODO consider using string lengths instead
	$pat = '/^\/?' . preg_quote(trim(config::path(), '/'), '/') . '\/(.*)$/';
	if(preg_match($pat, $url, $matches)) {
		$router = new RequestRouter($matches[1]);
		router::set($router);
		call_user_func(function() {
			include dirname(dirname(__DIR__)) . '/app/routes.php';
		});
		$router->route();
	} else {
		call_user_func(array(config::helper(), 'error'), 500, array('path' => $url));
	}
});

?>
