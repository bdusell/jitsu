<?php

namespace jitsu\app\handlers;

class Util {

	public static function require_prop($data, $name) {
		if(!\jitsu\Util::has_prop($data, $name)) {
			throw new \InvalidArgumentException("$name property is missing");
		}
		return $data->$name;
	}

	public static function normalize_namespace($value) {
		$value = trim($value, '\\');
		return $value === '' ? '\\' : "\\$value\\";
	}

	public static function pattern_to_regex($pat, &$has_trailing_slash = null) {
		$trailing_slash = false;
		$regex = preg_replace_callback(
			'#(:[A-Za-z_]+)|(\\*[A-Za-z_]+)|(/$)|(\\()|(\\))|(.+?)#',
			function($matches) use(&$has_trailing_slash) {
				if($matches[1] !== '') {
					return '([^/]+)';
				} elseif($matches[2] !== '') {
					return '(.*?)';
				} elseif($matches[3] !== '') {
					$has_trailing_slash = true;
					return '/?';
				} elseif($matches[4] !== '') {
					return '(?:';
				} elseif($matches[5] !== '') {
					return ')?';
				} else {
					return preg_quote($matches[6], '#');
				}
			},
			$pat
		);
		return '#^' . $regex . '$#';
	}
}

?>
