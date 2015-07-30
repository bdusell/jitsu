<?php

namespace jitsu\app\handlers;

class BadMethod extends Condition {

	public function matches($data) {
		return (bool) Util::require_prop($data, 'available_methods');
	}
}

?>
