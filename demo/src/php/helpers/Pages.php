<?php

use phrame\Util;
use phrame\ArrayUtil;
use phrame\RequestUtil;
use phrame\ResponseUtil;
use phrame\JSONUtil;

class Pages {

	private static $titles = array(
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Internal Server Error'
	);

	public static function error($code, $vars = null) {
		$response = ResponseUtil::instance();
		$request = RequestUtil::instance();
		$response->code($code);
		if($request->accepts('text/html')) {
			$vars['title'] = $code . ': ' . self::$titles[$code];
			self::page("errors/$code", $vars);
		} else {
			$response->content_type('text/plain');
			self::text("errors/$code", $vars);
		}
	}

	public static function redirect($url, $code = 303) {
		$response = ResponseUtil::instance();
		$config = AppConfig::instance();
		$response->redirect($config->make_url($url), $code);
	}

	public static function page($template, $vars) {
		$config = AppConfig::instance();
		$vars['base'] = $config->base_url;
		$vars['body'] = $template;
		$vars['scripts'] = array(
			'js/main.js'
		);
		$vars['stylesheets'] = array(
			'css/main.css'
		);
		Util::template(
			dirname(__DIR__) . '/views/common/main.html.php',
			$vars
		);
	}

	public static function text($template, $vars) {
		Util::template(
			dirname(__DIR__) . '/views/' . $template . '.txt.php',
			$vars
		);
	}
}

?>
