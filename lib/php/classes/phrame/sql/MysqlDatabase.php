<?php

namespace phrame\sql;

class MysqlDatabase extends Database {

	public function __construct($host, $database, $username, $password, $charset = 'utf8mb4') {
		parent::__construct(
			'mysql:host=' . $host .
			';dbname=' . $database .
			($charset === null ? '' : ';charset=' . $charset),
			$username,
			$password
		);
		if($charset !== null) {
			$this->execute('set names ' . $charset);
		}
	}
}

?>
