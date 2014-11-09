<?php

class AppRouter extends Router {

	public function routes() {
		$this->map('', function() {
			echo "this is the base path\n";
		});
		$this->map('index', function() {
			echo "this is the index\n";
		});
	}
}

?>
