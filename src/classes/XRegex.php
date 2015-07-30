<?php

namespace jitsu;

/* An object-oriented wrapper for PHP's PCRE patterns which offers a richer
 * API. */
class XRegex {

	public $pattern;
	public $count;
	public $offsets;

	public function __construct($arg) {
		$this->pattern = (
			$arg instanceof self ?
				$arg->pattern :
				call_user_func_array(
					array('RegexUtil', 'create'),
					func_get_args()
				)
		);
	}

	public function __toString() {
		return $this->pattern;
	}

	public function __call($name, $args) {
		if(array_key_exists($name, self::$unwrapped_methods)) {
			array_unshift($args, $this->pattern);
			return call_user_func_array(
				array('RegexUtil', $name),
				$args
			);
		} elseif(array_key_exists($name, self::$replace_methods)) {
			return $this->_replace($name, $args);
		} elseif(array_key_exists($name, self::$split_methods)) {
			return $this->_split($name, $args);
		} else {
			throw new BadMethodCallException(
				get_class() . '->' . $name . ' does not exist'
			);
		}
	}

	public static function __callStatic($name, $args) {
		if(array_key_exists($name, self::$static_methods)) {
			return call_user_func_array(
				array('RegexUtil', $name),
				$args
			);
		} else {
			throw new BadMethodCallException(
				get_class() . '::' . $name . ' does not exist'
			);
		}
	}

	private function _replace($name, $args) {
		return $this->_addref($name, $args, $this->count);
	}

	private function _split($name, $args) {
		return $this->_addref($name, $args, $this->offsets);
	}

	private function _addref($name, $args, &$ref) {
		array_unshift($args, $this->pattern);
		$args[] = &$ref;
		return call_user_func_array(
			array('RegexUtil', $name),
			$args
		);
	}

	private static $unwrapped_methods = array(
		'match' => true,
		'match_with_offsets' => true,
		'match_all' => true,
		'match_all_with_offsets' => true,
		'grep' => true,
		'inverted_grep' => true,
	);

	private static $replace_methods = array(
		'replace' => true,
		'replace_with' => true,
		'replace_filter' => true,
	);

	private static $split_methods = array(
		'split' => true,
		'filter_split' => true,
		'inclusive_split' => true,
	);

	private static $static_methods = array(
		'escape' => true,
	);
}

?>
