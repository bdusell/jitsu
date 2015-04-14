<?php

namespace phrame;

/* Utilities for dealing with files. */
class FileUtil {

	/* Alias for PHP `fprintf`. */
	public static function printf(/* $fout, $format, $arg1, ... */) {
		return call_user_func_array('fprintf', func_get_args());
	}

	/* Compute the MD5 hash of a file given its name. The result is a
	 * binary string of 16 bytes. */
	public static function md5($path) {
		return md5_file($path, true);
	}

	/* Like `md5`, but convert the result to a hex string. */
	public static function md5_hex($path) {
		return md5_file($path, false);
	}

	/* Compute the SHA1 hash of a file given its name. The result is a
	 * binary string of 20 bytes. */
	public static function sha1($path) {
		return sha1_file($path, true);
	}

	/* Like `sha1`, but convert the result to a hex string. */
	public static function sha1_hex($path) {
		return sha1_file($path, false);
	}

	/* Parse the extension of a file name. */
	public static function extension($path) {
		return pathinfo($filename, PATHINFO_EXTENSION);
	}
}

?>
