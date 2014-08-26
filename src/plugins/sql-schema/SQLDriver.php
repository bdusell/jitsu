<?php

class SQLDriver {

	const mysql = 0;
	const sqlite = 1;

	private $names = array(
		'mysql',
		'sqlite'
	);

	public static function toString($code) {
		return self::$names[$code];
	}
}

?>
