<?php

namespace jitsu\app\handlers;

class Path extends Condition {

	private $path;

	public function __construct($path, $callback) {
		parent::__construct($callback);
		$this->path = $path;
	}

	public function matches($data) {
		$path = Util::require_prop($data, 'path');
		$regex = Util::pattern_to_regex($this->path);
		$r = (bool) preg_match($regex, $path, $matches);
		$data->parameters = self::decode_params($matches);
		return $r;
	}

	private static function decode_params($matches) {
		$result = array();
		for($i = 1, $n = count($matches); $i < $n; ++$i) {
			$result[] = urldecode($matches[$i]);
		}
		return $result;
	}
}

?>
