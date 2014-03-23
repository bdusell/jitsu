<?php

class Config {

	private static $_base;

	private static $params = array(
		/* Param names go here */
	);

	public static function __callStatic($name, $args) {
		return self::$params[$name];
	}

	public static function site_name() {
		return 'Name of site';
	}

	public static function site_title() {
		return self::site_name();
	}

	public static function site_domain() {
		return 'domain.of.website';
	}

	public static function site_base_url() {
		return rtrim('http://' . $_SERVER['SERVER_NAME'] . self::base(), '/') . '/';
	}

	public static function contact_email() {
		return 'admin@' . self::site_domain();
	}

	public static function base() {
		return self::$_base;
	}

	public static function set_base($base) {
		self::$_base = $base;
	}

}

?>
