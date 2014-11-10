<?php

class AppRouter extends Router {

	public function routes() {
		$this->map('', array('Index', 'read'));
		$this->map('', array('Index', 'read'));
	}
}

?>
