<?php

/* General-purpose utility functions. */
class Util {

	public static function has($array, $key) {
		return isset($array[$key]);
	}

	public static function get($array, $key, $default = null, $valid = null) {
		return isset($array[$key]) &&
			(!is_array($valid) || in_array($array[$key], $valid, true)) ?
			$array[$key] : $default;
	}

	public static function id_cast($value, $min = 0) {
		if(is_null($value) || is_string($value) && !ctype_digit($value)) return null;
		$int = (int) $value;
		return $int < $min ? null : $int;
	}

	public static function uint_cast($value, $min = 0) {
		return self::id_cast($value, $min);
	}

	public static function bool_cast($value) {
		if(is_string($value)) {
			return strcasecmp($value, 'true') === 0
				|| strcasecmp($value, 'yes') === 0
				|| strcmp($value, '1') === 0;
		}
		return (bool) $value;
	}

	public static function is_empty_value($str) {
		return is_null($str) || $str === '';
	}

	public static function repr($var) {
		return var_export($var, true);
	}

	public static function str($var) {
		return print_r($var, true);
	}

	public static function print_html($s) {
?>
<code><?= Escape::html($s) ?></code>
<?php
	}

	public static function print_repr($var) {
		self::print_html(self::repr($var));
	}

	public static function print_str($var) {
		self::print_html(self::str($var));
	}

	public static function hilight($text, $substr, $tag) {
		return preg_replace('/' . preg_quote($substr) . '/u', "<$tag>\$0</$tag>", $text);
	}

	public static function get_extension($filename) {
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
		$result = array();
		foreach($array as $x) $result[$x] = true;
		return $result;
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
		// Irregular vowel y
		// -y => -ies
		$result = preg_replace('/([^aeiou])y$/', '$1ies', $s, 1, $count);
		if($count) return $result;

		// Sibilants
		// -s, -z, -x, -j, -sh, -tch, -zh => -ses, -zes, etc.
		$result = preg_replace('/(tch|sh|zh|[szxj])$/', '$1es', $s, 1, $count);
		if($count) return $result;

		// Simple addition of s
		// - => -s
		return $s . 's';
	}

	public static function template($filename, $vars = null) {
		if(!is_null($vars)) extract($vars);
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

	public static function format_stack_trace($trace) {
		$levels = array();
		foreach($trace as $level) {

			$func_name = '';
			if(isset($level['class'])) $func_name .= $level['class'];
			if(isset($level['type'])) $func_name .= $level['type'];
			$func_name .= $level['function'];

			$args = array();
			foreach($level['args'] as $arg) {
				$args[] = var_export($arg, true);
			}
			$args = join(', ', $args);

			if(isset($level['file']) && isset($level['line'])) {
				$levels[] = <<<TXT
{$level['file']}:{$level['line']}
$func_name($args)
TXT;
			}
		}
		return join("\n\n", $levels);
	}

}

?>
