<?php

class StringUtil {

	public static function cmp($a, $b) {
		return strcmp($a, $b);
	}

	public static function split_camel_case($str) {
		return preg_split('/(?<=[^A-Z])(?=[A-Z])/', $str);
	}

	public static function begins_with($str, $prefix) {
		return substr($str, 0, strlen($prefix)) === $prefix;
	}

	public static function ends_with($str, $suffix) {
		return substr($str, -strlen($suffix)) === $suffix;
	}

	public static function escape_html($text) {
		return html($text);
	}

	public static function escape_html_attr($text) {
		return html_attr($text);
	}

	public static function escape_url($text) {
		return urlencode($text);
	}

	public static function naive_pluralize($s) {
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

}

?>
