<?php

class Pages {

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
		Util::template('common/main.html.php', $vars);
	}

	public static function redirect($url, $code = 303) {
		Response::redirect(config::base_url() . $url, $code);
	}

	public static function page($template, $vars) {
		$vars['base'] = config::base_url();
		$vars['body'] = $template;
		$vars['scripts'] = array(
			config::is_production() ?
			'js/main.min.js' :
			'js/main.js'
		);
		$vars['stylesheets'] = array(
			config::is_production() ?
			'css/main.min.css' :
			'css/main.css'
		);
		Util::template('common/main.html.php', $vars);
	}
}

?>
