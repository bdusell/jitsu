<?php

namespace jitsu\app\handlers;

abstract class Condition extends Callback {

	public function handle($data) {
		if(($r = $this->matches($data))) {
			$this->trigger($data);
		}
		return $r;
	}

	abstract public function matches($data);
}

?>
