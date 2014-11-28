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
	$path = Request::path();
	$base_path = '/' . trim(config::path(), '/') . '/';
	$base_path_len = strlen($base_path);
	if(substr_compare($path, $base_path, 0, $base_path_len) == 0) {
		$route = substr($path, $base_path_len);
		$router = new RequestRouter($route);
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
