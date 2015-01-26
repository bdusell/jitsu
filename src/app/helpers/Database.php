<?php

class Database {

	private static $instance = null;
	private static $visitor;

	private static function create_instance() {
		$options = array(PDO::ATTR_PERSISTENT => config::persistent_db_connections());
		switch(config::sql_driver()) {
		case 'mysql':
			self::$instance = new \phrame\sql\MysqlDatabase(
				config::db_host(),
				config::db_name(),
				config::db_user(),
				config::db_password(),
				'utf8mb4',
				$options
			);
			self::$visitor = new \phrame\sql\visitors\MysqlVisitor(self::$instance);
			break;
		case 'sqlite':
			self::$instance = new \phrame\sql\SqliteDatabase(
				config::sqlite_file(),
				$options
			);
			self::$visitor = new \phrame\sql\visitors\SqliteVisitor(self::$instance);
			break;
		default:
			throw new \phrame\ConfigurationError(
				'unsupported SQL driver ' . Util::repr(config::sql_driver())
			);
		}
	}

	public static function instance() {
		if(self::$instance === null) {
			self::create_instance();
		}
		return self::$instance;
	}

	public static function interpret($n) {
		self::instance();
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
