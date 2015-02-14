<?php

use phrame\Util;
use phrame\Singleton;

class Database {

	use Singleton;

	private static $visitor;

	protected function instantiate() {
		$config = AppConfig::instance();
		$options = array(
			PDO::ATTR_PERSISTENT => $config->persistent_db_connections
		);
		switch($config->sql_driver) {
		case 'mysql':
			$result = new \phrame\sql\MysqlDatabase(
				$config->db_host,
				$config->db_name,
				$config->db_user,
				$config->db_password,
				'utf8mb4',
				$options
			);
			self::$visitor = new \phrame\sql\visitors\MysqlVisitor($result);
			break;
		case 'sqlite':
			$result = new \phrame\sql\SqliteDatabase(
				$config->sqlite_file,
				$options
			);
			self::$visitor = new \phrame\sql\visitors\SqliteVisitor(self::$instance);
			break;
		default:
			throw new \phrame\ConfigurationError(
				'unsupported SQL driver ' . Util::repr($config->sql_driver)
			);
		}
		return $result;
	}

	public static function interpret($n) {
		self::instance();
		return $n->accept(self::$visitor);
	}
};

?>
