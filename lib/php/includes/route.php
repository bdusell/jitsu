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
	/* TODO FIXME Allow slashes to be encoded in path components. Start by
	 * passing the un-escaped path here. */
	$path = Request::path();
	$base_path = '/' . trim(config::path(), '/') . '/';
	$base_path_len = strlen($base_path);
	if(substr_compare($path, $base_path, 0, $base_path_len) == 0) {
		$route = substr($path, $base_path_len);
		$router = new phrame\Router($route);
		router::set($router);
		call_user_func(function() {
			include dirname(dirname(dirname(__DIR__))) . '/src/app/routes.php';
		});
		if(config::buffer_output()) {
			ob_start();
		}
		try {
			$router->route();
			if(config::buffer_output()) {
				ob_end_flush();
			}
		} catch(Exception $e) {
			if(config::buffer_output()) {
				ob_end_clean();
			}
			call_user_func(array(config::helper(), 'error'), 500, array(
				'path' => $path,
				'message' => StringUtil::capture(function() use($e) {
					print_stack_trace($e);
				})
			));
		}
	} else {
		call_user_func(array(config::helper(), 'error'), 500, array(
			'path' => $path,
			'message' => (
				'Base path is misconfigured (got ' .
				Util::repr($path) . ', expected ' .
				Util::repr($base_path) . ')'
			)
		));
	}
});

?>
