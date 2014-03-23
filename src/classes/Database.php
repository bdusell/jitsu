<?php

class Database extends DatabaseSingleton {

	public function __construct() {
		/* XXX Won't work if this contructor isn't defined. */
		parent::__construct();
	}

	protected function database() { return 'database_name'; }
	protected function user()     { return 'database_user'; }
	protected function password() { return 'database_password'; }
	protected function charset()  { return 'utf8mb4'; }

};

?>
