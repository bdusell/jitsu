<?php

/* An object-oriented wrapper for PHP's PCRE patterns which offers a richer
 * API. */
class XRegex {

	public $pattern;
	public $count;
	public $offsets;

	public function __construct() {
		$this->pattern = call_user_func_array(
			array('RegexUtil', 'create'),
			func_get_args()
		);
	}

	public function __call($name, $args) {
		if(array_key_exists($name, self::$normal)) {
			array_unshift($args, $this->source);
			return call_user_func_array(
				array('RegexUtil', $name),
				$args
			);
		} elseif(array_key_exists($name, self::$replacefunc)) {
			return $this->_replace($name, $args);
		} elseif(array_key_exists($name, self::$splitfunc)) {
			return $this->_split($name, $args);
		} else {
			throw new BadMethodCallException(
				get_class() . '->' . $name . ' does not exist'
			);
		}
	}

	private static $normal = array(
		'match' => true,
		'match_with_offsets' => true,
		'match_all' => true,
		'match_all_with_offsets' => true,
		'grep' => true,
		'inverted_grep' => true,
	);

	private static $replacefunc = array(
		'replace' => true,
		'replace_with' => true,
		'replace_filter' => true,
	);

	private static $splitfunc = array(
		'split' => true,
		'filter_split' => true,
		'inclusive_split' => true,
	);

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
}

?>
