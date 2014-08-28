<?php

/* General-purpose utility functions. */
class Util {

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
			return strcasecmp($value, 'true')
				|| strcasecmp($value, 'yes')
				|| strcmp($value, '1');
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
<pre><?= Escape::html($s) ?></pre>
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
