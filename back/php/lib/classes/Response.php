<?php

class Response {

	public static function code($code = null) {
		if(is_null($code)) return http_response_code();
		else return http_response_code($code);
	}

	public static function header($name, $value) {
		header("$name: $value");
	}

	public static function type($type) {
		self::header('Content-Type', $type);
	}

	public static function cookie($name, $value, $lifespan = null, $path = null) {
		setcookie(
			$name,
			$value,
			$lifespan === null ? 0 : time() + $lifespan,
			$path === null ? '' : $path
		);
	}

	public static function delete_cookie($name, $path = null) {
		self::cookie($name, '', -3600, $path);
	}

	public static function json($obj, $pretty = true) {
		self::type('application/json');
		echo Serialize::json($obj, $pretty);
	}

	public static function file($path, $content_type) {
		self::type($content_type);
		readfile($path);
	}
}

?>
