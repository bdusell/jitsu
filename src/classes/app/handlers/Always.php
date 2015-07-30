<?php

namespace jitsu\app\handlers;

class Always extends Callback {

	public function handle($data) {
		$this->trigger($data);
		return true;
	}
}

?>
