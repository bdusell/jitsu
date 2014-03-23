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

	public static function html($text) {
		return htmlspecialchars($text);
	}

	public static function is_empty_string($str) {
		return is_null($str) || $str === '';
	}

	public static function html_attr($text) {
		return htmlspecialchars($text, ENT_QUOTES);
	}

	public static function xml($text) {
		return htmlspecialchars($text, ENT_NOQUOTES);
	}

	public static function sql_like($text) {
		return str_replace(array('%', '_'), array('%%', '_'), $text);
	}

	public static function repr($var) {
		return var_export($var, true);
	}

	public static function str($var) {
		return print_r($var, true);
	}

	public static function html_print($s) {
?>
<pre><?= self::html($s) ?></pre>
<?php
	}

	public static function print_repr($var) {
		self::html_print(self::repr($var));
	}

	public static function print_str($var) {
		self::html_print(self::str($var));
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
		return strtolower(join('_', preg_split('/(?<=.)(?=[A-Z])/', $s)));
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
