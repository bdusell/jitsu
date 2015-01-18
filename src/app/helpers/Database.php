<?php

class Database {

	private static $instance = null;

	private static function create_instance() {
		switch(config::sql_driver()) {
		case 'mysql':
			return new \phrame\sql\MysqlDatabase(
				config::db_host(),
				config::db_name(),
				config::db_user(),
				config::db_password()
			);
		case 'sqlite':
			return new \phrame\sql\SqliteDatabase(
				config::sqlite_file()
			);
		default:
			throw new \phrame\ConfigurationError(
				'unsupported SQL driver ' . Util::repr(config::sql_driver())
			);
		}
	}

	private static function get_instance() {
		if(self::$instance === null) {
			self::$instance = self::create_instance();
		}
		return self::$instance;
	}

	public static function __callStatic($name, $args) {
		return call_user_func_array(
			array(self::get_instance(), $name),
			$args
		);
	}
};

?>
