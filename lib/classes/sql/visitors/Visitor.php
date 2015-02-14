<?php

namespace phrame\sql\visitors;

use phrame\StringUtil;

class Visitor {

	const METHOD_PREFIX = 'visit';
	const CLASS_PREFIX = 'phrame\\sql\\ast\\';

	/* Whenever `visitSomeClass()` is called but not found, try calling
	 * `visitParentClassOfSomeClass`. */
	public function __call($name, $args) {
		if(($base_name = StringUtil::remove_prefix($name, self::METHOD_PREFIX)) !== null) {
			$parent = get_parent_class(self::CLASS_PREFIX . $base_name);
			if($parent && ($parent_base_name = StringUtil::remove_prefix($parent, self::CLASS_PREFIX)) !== null) {
				return call_user_func_array(
					array($this, self::METHOD_PREFIX . $parent_base_name),
					$args
				);
			}
		}
		throw new \BadMethodCallException(
			get_class($this) . '->' . $name . ' is not a method'
		);
	}
}

?>
