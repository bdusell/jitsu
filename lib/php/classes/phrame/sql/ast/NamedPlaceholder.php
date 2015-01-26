<?php

namespace phrame\sql\ast;

/* A named placeholder for a bind parameter.
 *
 * <named-placeholder> ->
 *   ":[some name]"
 */
class NamedPlacholder extends Placeholder {

	public $name;

	public function __construct($name) {
		parent::__construct(array('name' => $name));
		$this->validate_string('name');
	}
}

?>
