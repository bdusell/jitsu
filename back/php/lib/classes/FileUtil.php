<?php

class FileUtil {

	public static function extension($path) {
		return pathinfo($filename, PATHINFO_EXTENSION);
	}
}

?>
