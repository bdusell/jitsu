<?php

class AccessLog extends DatabaseSingleton {

	public function __construct() {
		parent::__construct();
	}

	protected function database() { return 'access_log_database'; }
	protected function user()     { return 'access_log_user'; }
	protected function password() { return 'access_log_password'; }

	private static $table = 'access_log_table';

	public static function log() {
		$table = self::$table;
		self::execute(<<<SQL
insert into $table (
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
