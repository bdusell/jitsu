<?php

namespace jitsu\app\handlers;

class Configure implements \jitsu\app\Handler {

	public function handle($data) {
		$config = Util::require_prop($data, 'config');
		$path = $config->remove_path($data->request->path());
		if($path === null) {
			throw new \LogicException('misconfigured base path');
		}
		$data->path = $path;
		$data->available_methods = array();
		if($config->has('sql_driver')) {
			list($database, $visitor) = self::get_database($data);
			$data->database = $database;
			$data->sql_visitor = $visitor;
		}
	}

	private static function get_database($data) {
		$config = $data->config;
		$options = array(
			\PDO::ATTR_PERSISTENT =>
				$config->persistent_database_connections
		);
		switch($config->sql_driver) {
		case 'sqlite':
			$db = new LazySqliteDatabase(
				$config->sqlite_file,
				$options
			);
			return array($db, new \jitsu\sql\visitors\SqliteVisitor($db));
		case 'mysql':
			$db = new LazyMysqlDatabase(
				$config->database_host,
				$config->database_name,
				$config->database_user,
				$config->database_password,
				'utf8mb4',
				$options
			);
			return array($db, new \jitsu\sql\visitors\MysqlVisitor($db));
		}
		throw new \InvalidArgumentException(
			'SQL driver ' . $data->sql_driver . ' not recognized'
		);
	}
}

?>
