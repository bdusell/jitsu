<?php

namespace phrame\sql\ast;

abstract class Node {

	public function accept($v) {
		$class = get_called_class();
		$end = strrchr($class, '\\');
		$end = $end === false ? $class : substr($end, 1);
		return call_user_func(array($v, 'visit' . $end), $this);
	}
}

?>
