<?php

class AppRouter extends Router {

	public function routes() {
		$this->map('GET', '', array('IndexController', 'show'));
		$this->map('GET', 'index', array('IndexController', 'show'));
		$this->map('GET', 'users/:id', array('UserController', 'show'));
		$this->map('GET', 'users/', array('UserController', 'index'));
		$this->map('POST', 'users/', array('UserController', 'create'));
	}
}

?>
