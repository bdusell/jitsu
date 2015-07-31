<?php

namespace DemoApp;

class Util {

	private static $titles = array(
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Internal Server Error'
	);

	public static function page($data, $template, $vars) {
		$config = $data->config;
		$vars['base'] = $config->base_url;
		$vars['body'] = $template;
		$vars['site_name'] = $config->site_name;
		$vars['scripts'] = array('js/main.js');
		$vars['stylesheets'] = array('css/main.css');
		\jitsu\Util::template(
			dirname(__DIR__) . '/views/common/main.html.php',
			$vars
		);
	}

	public static function error($data, $code, $vars = null) {
		$request = $data->request;
		$response = $data->response;
		$response->code($code);
		if($request->accepts('text/html')) {
			$vars['title'] = $code . ': ' . self::$titles[$code];
			self::page($data, "errors/$code", $vars);
		} else {
			$response->content_type('text/plain');
			self::text($data, "errors/$code", $vars);
		}
	}

	public static function text($template, $vars) {
		\jitsu\Util::template(
			dirname(__DIR__) . '/views/' . $template . '.txt.php',
			$vars
		);
	}

	public static function redirect($data, $url, $code = 303) {
		$response = $data->response;
		$config = $data->config;
		$response->redirect($config->make_url($url), $code);
	}

	public static function wrap($data, $callback) {
		$response = $data->response;
		$config = $data->config;
		$buffering = $config->output_buffering;
		ini_set('default_charset', 'UTF-8');
		if($buffering) {
			$response->start_output_buffering();
		}
		try {
			call_user_func($callback, $data);
		} catch(\Exception $e) {
			if($buffering) {
				$response->clear_output_buffer();
			}
			$data->exception = $e;
			Errors::internal_error($data);
			return;
		}
		if($buffering) {
			$response->flush_output_buffer();
		}
	}

	public static function sql($data, $node) {
		return $node->accept($data->sql_visitor);
	}
}

?>
