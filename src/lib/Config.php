<?php

/* API to phrame configuration. */
class Config {

	public static function debug() {
		global $DEBUG;
		return $DEBUG;
	}

	public static function base_dir() {
		global $BASE_DIR;
		return $BASE_DIR;
	}

	public static function site_name() {
		global $SITE_NAME;
		return $SITE_NAME;
	}

	public static function protocol() {
		global $PROTOCOL;
		return $PROTOCOL;
	}

	public static function subdomain() {
		global $SUBDOMAIN;
		return $SUBDOMAIN;
	}

	public static function domain() {
		global $DOMAIN;
		return $DOMAIN;
	}

	public static function email() {
		global $EMAIL;
		return $EMAIL;
	}

	public static function artificial_server_name() {
		return ltrim(self::subdomain() . '.' . self::domain(), '.');
	}

	public static function server_name() {
		return $_SERVER['SERVER_NAME'];
	}

	private static function _base_url($server_name) {
		return self::protocol() . '://' . rtrim(rtrim($server_name, '/') . '/' . self::base_dir(), '/') . '/';
	}

	public static function artificial_base_url() {
		return self::_base_url(self::expected_server_name());
	}

	public static function base_url() {
		return self::_base_url(self::server_name());
	}
}

?>
