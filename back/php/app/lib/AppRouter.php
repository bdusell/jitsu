<?php

class AppRouter extends Router {

	public function routes() {
		$this->map('', array('IndexController', 'show'));
		$this->map('index', array('IndexController', 'show'));
	}
}

?>
