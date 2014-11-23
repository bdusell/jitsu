<?php

class AppHelper {

	private static $titles = array(
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Internal Server Error'
	);

	public static function error($code, $vars = null) {
		Response::code($code);
		$root = dirname(__DIR__);
		$filename = "$root/views/errors/$code.html.php";
		if(file_exists($filename)) {
			$vars['body'] = "errors/$code";
		}
		$vars['title'] = $code . ': ' . self::$titles[$code];
		Util::template('main.html.php', $vars);
	}

	public static function page($template, $vars) {
		$vars['base'] = config::base_url();
		$vars['body'] = $template;
		Util::template('main.html.php', $vars);
	}
}

?>
