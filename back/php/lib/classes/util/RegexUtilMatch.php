<?php

class RegexUtilMatch {

	public $groups;
	public $indexes;

	public function __construct($groups, $indexes = null) {
		$this->groups = $groups;
		$this->indexes = $indexes;
	}

	public function group($i) {
		return $this->groups[$i];
	}
}

?>
