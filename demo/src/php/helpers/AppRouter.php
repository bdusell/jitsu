<?php

use phrame\Singleton;
use phrame\http\Router;

class AppRouter {

	use Singleton;

	protected function instantiate() {
		return new Router(dirname(__DIR__) . '/routes.php');
	}
}

?>
