<?php

/* General-purpose utility functions. */
class Util {

	public static function str($var) {
		return print_r($var, true);
	}

	public static function repr($var) {
		return var_export($var, true);
	}

	public static function p(/* *args */) {
		$first = true;
		foreach(func_get_args() as $arg) {
			if(!$first) echo ' ';
			echo self::repr($arg);
			$first = false;
		}
		echo "\n";
		if(func_num_args() > 0) {
			return func_get_arg(0);
		}
	}

	public static function file_extension($filename) {
		return pathinfo($filename, PATHINFO_EXTENSION);
	}

	public static function camel_case($s) {
		$parts = array();
		foreach(explode('_', $s) as $ss) $parts[] = ucfirst($ss);
		return join('', $parts);
	}

	public static function underscores($s) {
		return strtolower(join('_', preg_split('/(?<=[^A-Z])(?=[A-Z])/', $s)));
	}

	public static function begins_with($s, $prefix) {
		return substr($s, 0, strlen($prefix)) === $prefix;
	}

	public static function ends_with($s, $suffix) {
		return substr($s, -strlen($suffix)) === $suffix;
	}

	public static function to_set($array) {
		return array_fill_keys($array, true);
	}

	public static function has_only_keys($array, $keys, &$unexpected = null) {
		$gather = func_num_args() > 2;
		$key_set = self::to_set($keys);
		foreach($array as $key => $value) {
			if(!isset($key_set[$key])) {
				if($gather) {
					$unexpected[] = $key;
				} else {
					return false;
				}
			}
		}
		return !$unexpected;
	}

	public static function has_keys($array, $keys, &$missing = null) {
		$gather = func_num_args() > 2;
		foreach($keys as $key) {
			if(!isset($array[$key])) {
				if($gather) {
					$missing[] = $key;
				} else {
					return false;
				}
			}
		}
		return !$missing;
	}

	public static function has_exact_keys($array, $keys, &$unexpected = null, &$missing = null) {
		$gather = func_num_args() > 2;
		$key_set = self::to_set($keys);
		foreach($array as $key => $value) {
			if(isset($key_set[$key])) {
				unset($key_set[$key]);
			} elseif($gather) {
				$unexpected[] = $key;
			} else {
				return false;
			}
		}
		if($gather) {
			$missing = array_keys($key_set);
		}
		return !$key_set && !$unexpected;
	}

	public static function pluralize($s) {
		// Vowel y
		// -y => -ies
		$result = preg_replace('/([^aeiou])y$/', '$1ies', $s, 1, $count);
		if($count) return $result;

		// Sibilants
		// -s, -z, -x, -j, -sh, -tch, -zh => -ses, -zes, etc.
		$result = preg_replace('/([^aeiouy]ch|[sz]h|[szxj])$/', '$1es', $s, 1, $count);
		if($count) return $result;

		// Simple addition of s
		// - => -s
		return $s . 's';
	}

	public static function template($filename, $vars = null) {
		if($vars) extract($vars);
		include $filename;
	}

	public static function extend(&$dest, $src) {
		foreach($src as $k => $v) {
			$dest[$k] = $v;
		}
		return $dest;
	}

	/* Preconditions: $dest is an array, $key may or may not be in $dest,
	 * $src_value may be anything. */
	private static function _extend_r(&$dest, $key, $src_value) {
		if(isset($dest[$key])) {
			$dest_value = $dest[$key];
			if(
				is_array($dest_value) &&
				is_array($src_value) &&
				self::is_associative($dest_value) &&
				self::is_associative($src_value)
			) {
				foreach($src_value as $k => $v) {
					self::_extend_r($dst_value, $k, $v);
				}
			} else {
				$dest[$key] = $src_value;
			}
		} else {
			$dest[$key] = $src_value;
		}
	}

	public static function extend_r(&$dest, $src) {
		foreach($src as $k => $v) {
			self::_extend_r($dest, $k, $v);
		}
		return $dest;
	}

	public static function is_sequential($array) {
		$array = array_keys($array);
		return $array === array_keys($array);
	}

	public static function is_associative($array) {
		return !self::is_sequential($array);
	}

	public static function values(/* $array, $keys ... */) {
		$args = func_get_args();
		$array = array_shift($args);
		if(count($args) == 1 && is_array($args[0])) {
			$keys = $args[0];
		} else {
			$keys = $args;
		}
		$result = array();
		foreach($keys as $key) {
			$result[] = $array[$key];
		}
		return $result;
	}
}

?>
