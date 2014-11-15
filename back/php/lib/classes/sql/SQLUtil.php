<?php

class SQLUtil {

	public static function escape_like($text) {
		return str_replace(array('%', '_'), array('\\%', '\\_'), $text);
	}
}

?>
