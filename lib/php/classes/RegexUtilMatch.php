<?php

class RegexUtilMatch {

	public $groups;
	public $indexes;

	public function __construct($groups, $indexes = null) {
		$this->groups = $groups;
		$this->indexes = $indexes;
	}

	public function __toString() {
		return implode(', ', $this->groups);
	}

	public function group($i) {
		return $this->groups[$i];
	}
}

?>
