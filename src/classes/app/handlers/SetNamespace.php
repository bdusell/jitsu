<?php

namespace jitsu\app\handlers;

class SetNamespace implements \jitsu\app\Handler {

	private $app_namespace;

	public function __construct($app_namespace) {
		$this->app_namespace = Util::normalize_namespace($app_namespace);
	}

	public function handle($data) {
		$data->app_namespace = $this->app_namespace;
	}
}

?>
