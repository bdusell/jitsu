<?php

class AccessLog extends DatabaseSingleton {

	public function __construct() {
		parent::__construct();
	}

	protected function database() {
		global $ACCESS_LOG_DATABASE;
		return $ACCESS_LOG_DATABASE;
	}

	protected function user() {
		global $ACCESS_LOG_USER;
		return $ACCESS_LOG_USER;
	}

	protected function password() {
		global $ACCESS_LOG_PASSWORD;
		return $ACCESS_LOG_PASSWORD;
	}

	public static function log() {
		global $ACCESS_LOG_TABLE;
		self::execute(<<<SQL
insert into $ACCESS_LOG_TABLE (
	http_user_agent, http_referer, remote_addr, request_uri, request_time,
	request_time_float, remote_host, remote_user
) values (
	?, ?, ?, ?, ?,
	?, ?, ?
)
SQL
		, Util::get($_SERVER, 'HTTP_USER_AGENT')
		, Util::get($_SERVER, 'HTTP_REFERER')
		, Util::get($_SERVER, 'REMOTE_ADDR')
		, Util::get($_SERVER, 'REQUEST_URI')
		, Util::get($_SERVER, 'REQUEST_TIME')
		, Util::get($_SERVER, 'REQUEST_TIME_FLOAT')
		, Util::get($_SERVER, 'REMOTE_HOST')
		, Util::get($_SERVER, 'REMOTE_USER')
		);
	}

}

?>
