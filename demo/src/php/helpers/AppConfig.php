<?php

use phrame\Singleton;
use phrame\SiteConfig;

class AppConfig {

	use Singleton;

	protected function instantiate() {
		$config = new SiteConfig(dirname(__DIR__) . '/config.php');
		$config->read('./config.php');
		return $config;
	}
}

?>
