<?php

class WebUtil {

	public static function escape_html($text) {
		return html($text);
	}

	public static function escape_html_attr($text) {
		return html_attr($text);
	}

	public static function escape_url($text) {
		return urlencode($text);
	}
}

?>
