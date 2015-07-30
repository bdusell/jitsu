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
		if(isset($config->sql_driver)) {
			self::add_database($data);
		}
	}

	private static function add_database($data) {
		$config = $data->config;
		$options = array(
			\PDO::ATTR_PERSISTENT =>
				$config->persistent_database_connections
		);
		switch($config->sql_driver) {
		case 'sqlite':
			$data->database = new LazySqliteDatabase(
				$config->sqlite_file,
				$options
			);
			$data->sql_visitor = new \jitsu\sql\visitors\SqliteVisitor($data->database);
			return;
		case 'mysql':
			$data->database = new LazyMysqlDatabase(
				$config->database_host,
				$config->database_name,
				$config->database_user,
				$config->database_password,
				'utf8mb4',
				$options
			);
			$data->sql_visitor = new \jitsu\sql\visitors\MysqlVisitor($data->database);
			return;
		}
		throw new \InvalidArgumentException(
			'SQL driver ' . $data->sql_driver . ' not recognized'
		);
	}
}

?>
