<?php

class _DatabaseHelper extends SQLDatabase {

	use Singleton;

	public function __construct() {
		$this->driver = config::sql_driver();
		$this->database = config::sql_database();
		parent::__construct();
	}
}

class Database {

	public static function __callStatic($name, $args) {
		return call_user_func_array(
			array(_DatabaseHelper::instance(), $name),
			$args
		);
	}
};

?>
