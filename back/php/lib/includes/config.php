<?php

class config {

	private static $vars = array(
		'is_production' => false,
		'base_url'      => '',
		'base_path'     => '',
		'router'        => 'AppRouter'
	);

	private static $post_process = array(
		'base_url' => array('self', 'process_base_url')
	);

	private static $modules = array();

	public static function set($name, $value) {
		self::$vars[$name] = $value;
	}

	public static function get($name) {
		return self::$vars[$name];
	}

	public static function modules(/* $name1, ... */) {
		if(func_num_args() > 0) {
			foreach(func_get_args() as $arg) {
				self::$modules[] = $arg;
			}
		} else {
			return self::$modules;
		}
	}

	private static function process_base_url($url) {
		$parts = parse_url($url);
		if(array_key_exists('path', $parts)) {
			self::base_path($parts['path']);
		}
		return $url;
	}

	public static function __callStatic($name, $args) {
		if(!array_key_exists($name, self::$vars)) {
			throw new BadMethodCallException(
				get_class() . '::' . $name . ' does not exist'
			);
		}
		switch(count($args)) {
		case 0:
			return self::$vars[$name];
		case 1:
			$value = $args[0];
			if(array_key_exists($name, self::$post_process)) {
				$val = call_user_func(self::$post_process[$name], $value);
			}
			self::$vars[$name] = $value;
			return;
		default:
			throw new BadMethodCallException('too many arguments');
		}
	}
}

?>
