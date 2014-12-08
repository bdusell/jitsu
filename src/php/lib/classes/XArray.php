<?php

/* An object-oriented wrapper around the built-in PHP array type which offers
 * a richer API. */
class XArray {

	public $value;

	public function __construct($value = null) {
		$this->value = (
			$value === null ?
				array() :
				$value instanceof self ?
					$value->value :
					$value
		);
	}

	public function __toString() {
		return $this->join(', ');
	}

	public function __call($name, $args) {
		$func = array('ArrayUtil', $name);
		self::_unbox($args);
		if(array_key_exists($name, self::$inplace)) {
			array_unshift($args, null);
			$args[0] = &$this->value;
			return call_user_func_array($func, $args);
		} else {
			array_unshift($args, $this->value);
			if(array_key_exists($name, self::$dowrap)) {
				return new XArray(
					call_user_func_array($func, $args)
				);
			} elseif(array_key_exists($name, self::$nowrap)) {
				return call_user_func_array($func, $args);
			}
		}
		throw new BadMethodCallException(
			get_class() . '->' . $name . ' does not exist'
		);
	}

	public static function __callStatic($name, $args) {
		self::_unbox($args);
		if(array_key_exists($name, self::$ctors)) {
			return new XArray(
				call_user_func_array(
					array('ArrayUtil', $name),
					$args
				)
			);
		}
		throw new BadMethodCallException(
			get_class() . '::' . $name . ' does not exist'
		);
	}

	public function has_only_keys($keys, &$unexpected = null) {
		list($keys) = self::_unbox_copy($keys);
		switch(func_num_args()) {
		case 1:
			return ArrayUtil::has_only_keys($this->value, $keys);
		default:
			return ArrayUtil::has_only_keys($this->value, $keys, $unexpected);
		}
	}

	public function has_keys($keys, &$missing = null) {
		list($keys) = self::_unbox_copy($keys);
		switch(func_num_args()) {
		case 1:
			return ArrayUtil::has_keys($this->value, $keys);
		default:
			return ArrayUtil::has_keys($this->value, $keys, $missing);
		}
	}

	public function has_exact_keys($keys, &$unexpected = null, &$missing = null) {
		list($keys) = self::_unbox_copy($keys);
		switch(func_num_args()) {
		case 1:
			return ArrayUtil::has_exact_keys($this->value);
		default:
			return ArrayUtil::has_exact_keys($this->value, $unexpected, $missing);
		}
	}

	public function join($str) {
		list($str) = self::_unbox_copy($str);
		return StringUtil::join($this->value, $str);
	}

	private static function _unbox(&$args) {
		$tmp = $args;
		$args = array();
		foreach($tmp as $arg) {
			if($arg instanceof self) {
				$arg = $arg->value;
			}
			$args[] = $arg;
		}
	}

	private static function _unbox_copy() {
		$args = func_get_args();
		self::_unbox($args);
		return $args;
	}

	private static $ctors = array(
		'range' => true,
		'from_pairs' => true,
		'from_lists' => true,
		'fill' => true,
	);

	private static $dowrap = array(
		'keys' => true,
		'concat' => true,
		'slice' => true,
		'pair_slice' => true,
		'reverse' => true,
		'reverse_pairs' => true,
		'to_set' => true,
		'pad' => true,
		'keys_of' => true,
		'pluck' => true,
		'pick' => true,
		'invert' => true,
		'extend' => true,
		'rextend' => true,
		'chunks' => true,
		'map' => true,
		'filter' => true,
		'filter_pairs' => true,
		'difference' => true,
		'pair_difference' => true,
		'key_difference' => true,
		'value_difference' => true,
		'pair_intersection' => true,
		'key_intersection' => true,
		'value_intersection' => true,
		'unique_values' => true,
		'random_keys' => true,
		'lower_keys' => true,
		'upper_keys' => true,
		'count_values' => true,
	);

	private static $nowrap = array(
		'size' => true,
		'length' => true,
		'get' => true,
		'has_key' => true,
		'key_of' => true,
		'index_of' => true,
		'contains' => true,
		'value_at' => true,
		'pair_at' => true,
		'key_at' => true,
		'sum' => true,
		'product' => true,
		'reduce' => true,
		'random_key' => true,
		'random_value' => true,
		'random_pair' => true,
		'is_sequential' => true,
	);

	private static $inplace = array(
		'getref' => true,
		'remove' => true,
		'append' => true,
		'append_all' => true,
		'push' => true,
		'pop' => true,
		'shift' => true,
		'unshift' => true,
		'assign_slice' => true,
		'remove_slice' => true,
		'apply' => true,
		'traverse_leaves' => true,
		'shuffle' => true,
		'sort' => true,
		'reverse_sort' => true,
		'locale_sort' => true,
		'sort_pairs' => true,
		'reverse_sort_pairs' => true,
		'sort_keys' => true,
		'reverse_sort_keys' => true,
		'human_sort_values' => true,
		'ihuman_sort_values' => true,
	);
}

?>
