<?php

class config {

	private static $vars = array(
		'scheme'        => 'http',
		'host'          => 'localhost',
		'path'          => '',
		'is_production' => false,
		'router'        => 'AppRouter',
		'helper'        => 'AppHelper'
	);

	/*
	private static $setters = array(
		'base_url' => array('self', 'set_base_url')
	);

	private static $getters = array(
		'base_url' => array('self', 'get_base_url')
	);
	 */

	private static $modules = array();

	public static function set($name, $value) {
		self::$vars[$name] = $value;
	}

	public static function get($name) {
		return self::$vars[$name];
	}

	public static function base_url($url = null) {
		if($url === null) {
			$path = self::path();
			return (
				self::scheme() . '://' .
				self::host() . '/' .
				($path === '' ? '' : trim($path, '/') . '/')
			);
		} else {
			$parts = parse_url($url);
			foreach(array('scheme', 'host', 'path') as $name) {
				if(array_key_exists($name, $parts)) {
					self::set($name, $parts[$name]);
				}
			}
		}
	}

	public static function __callStatic($name, $args) {
		if(!array_key_exists($name, self::$vars)) {
			self::_nomethod($name);
		}
		switch(count($args)) {
		case 0:
			return self::$vars[$name];
		case 1:
			self::$vars[$name] = $args[0];
			return;
		default:
			self::_toomanyargs();
		}
	}

	private static function _nomethod($name) {
		throw new BadMethodCallException(
			get_class() . '::' . $name . ' does not exist'
		);
	}

	private static function _toomanyargs() {
		throw new BadMethodCallException('too many arguments');
	}
}

?>
