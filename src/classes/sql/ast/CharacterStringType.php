<?php

namespace jitsu\sql\ast;

class CharacterStringType extends Type {

	const ASCII = 'ASCII';
	const UNICODE = 'UNICODE';

	const CASE_SENSITIVE = 'BINARY';
	const CASE_INSENSITIVE = 'CASE INSENSITIVE';

	public $character_set;
	public $collation;

	public function __construct($attrs) {
		parent::__construct($attrs);
		$this->validate_const('character_set');
		$this->validate_const('collation');
	}

	public function charset($charset) {
		$this->character_set = $charset;
		return $this;
	}

	public function collation($collation) {
		$this->collation = $collation;
		return $this;
	}
}

?>
