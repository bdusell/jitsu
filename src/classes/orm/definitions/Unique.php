<?php

namespace jitsu\orm\definitions;

class Unique extends Group {

	protected function mark_single($def) {
		$def->unique();
	}

	protected function wrap_group($names) {
		return new \jitsu\sql\ast\UniqueConstraint(array(
			'columns' => $names
		));
	}
}

?>
