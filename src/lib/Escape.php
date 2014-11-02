<?php

class Escape {

	public static function html($text) {
		return htmlspecialchars($text);
	}

	public static function html_attr($text) {
		return htmlspecialchars($text, ENT_QUOTES);
	}

	public static function xml($text) {
		return htmlspecialchars($text, ENT_NOQUOTES);
	}

	public static function sql_like($text) {
		return str_replace(array('%', '_'), array('\\%', '\\_'), $text);
	}
}

?>
