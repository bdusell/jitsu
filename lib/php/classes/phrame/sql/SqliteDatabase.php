<?php

namespace phrame\sql;

class SqliteDatabase extends Database {

	public function __construct($filename) {
		parent::__construct('sqlite:' . $filename);
		$this->execute('pragma foreign_keys = on');
	}
}

?>
