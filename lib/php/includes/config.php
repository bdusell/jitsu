<?php

class config {

	private static $vars = array(
		'dir'           => null,
		'document_root' => null,
		'scheme'        => 'http',
		'host'          => 'localhost',
		'path'          => '',
		'is_production' => false,
		'show_errors'   => true,
		'helper'        => 'Pages'
	);

	private static $modules = array();

	public static function set($name, $value) {
		self::$vars[$name] = $value;
	}

	public static function get($name) {
		return self::$vars[$name];
	}

	public static function is_production($value = null) {
		if(func_num_args() > 0) {
			self::set('is_production', $value);
			self::set('show_errors', !$value);
		} else {
			return self::get('is_production');
		}
	}

	public static function base_url($url = null) {
		if($url === null) {
			return self::make_url('');
		} else {
			$parts = parse_url($url);
			foreach(array('scheme', 'host', 'path') as $name) {
				if(array_key_exists($name, $parts)) {
					self::set($name, $parts[$name]);
				}
			}
		}
	}

	public static function make_path($rel_path) {
		$base_path = self::path();
		return '/' . (
			$base_path === '' ?
			'' :
			trim($base_path, '/') . '/'
		) . $rel_path;
	}

	public static function make_url($rel_path) {
		return (
			self::scheme() . '://' .
			self::host() .
			self::make_path($rel_path)
		);
	}

	public static function locale(/* $arg1, ... */) {
		if(func_num_args() > 0) {
			setlocale(LC_ALL, func_get_args());
		} else {
			return setlocale('0');
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
