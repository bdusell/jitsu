<?php

use phrame\Singleton;
use phrame\Application;

class App {

	use Singleton;

	protected function instantiate() {
		return new Application(
			AppRouter::instance(),
			AppConfig::instance()
		);
	}
}

?>
