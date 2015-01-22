<?php

class Database {

	private static $_instance = null;
	private static $visitor;
	private static $validator;

	private static function create_instance() {
		self::$validator = new \phrame\sql\visitors\ValidationVisitor();
		switch(config::sql_driver()) {
		case 'mysql':
			$db = new \phrame\sql\MysqlDatabase(
				config::db_host(),
				config::db_name(),
				config::db_user(),
				config::db_password()
			);
			self::$visitor = new \phrame\sql\visitors\MysqlVisitor($db);
			return $db;
		case 'sqlite':
			$db = new \phrame\sql\SqliteDatabase(
				config::sqlite_file()
			);
			self::$visitor = new \phrame\sql\visitors\SqliteVisitor($db);
			return $db;
		default:
			throw new \phrame\ConfigurationError(
				'unsupported SQL driver ' . Util::repr(config::sql_driver())
			);
		}
	}

	public static function instance() {
		if(self::$_instance === null) {
			self::$_instance = self::create_instance();
		}
		return self::$_instance;
	}

	public static function interpret($n) {
		self::instance();
		if(!config::is_production()) {
			$n->accept(self::$validator);
		}
		return $n->accept(self::$visitor);
	}

	public static function __callStatic($name, $args) {
		return call_user_func_array(
			array(self::instance(), $name),
			$args
		);
	}
};

?>
