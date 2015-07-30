<?php

namespace jitsu\app\handlers;

class Callback implements \jitsu\app\Handler {

	private $callback;

	public function __construct($callback) {
		$this->callback = $callback;
	}

	public function handle($data) {
		return self::trigger($data);
	}

	public function trigger($data) {
		$callback = $this->callback;
		if(is_string($callback)) {
			$namespace = Util::require_prop($data, 'app_namespace');
			$callback = Util::normalize_namespace($namespace) . $callback;
		}
		return call_user_func($callback, $data);
	}
}

?>
