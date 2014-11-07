<?php

/* Object-oriented interface to your application's main database. */
class Database extends SQLDatabase {

	public function __construct() {
		/* XXX Won't work if this contructor isn't defined. */
		parent::__construct();
	}

	protected function driver()   { return 'sqlite'; /* 'sqlite' or 'mysql' */ }
	protected function database() { return './database.db'; }
	protected function user()     { return null; }
	protected function password() { return null; }
	protected function charset()  { return null; }

};

?>
