<?php

/* Application-specific utilities for dealing with the database. */
class DatabaseUtil {

	/* Other stuff goes here. */

	public static function escape_regexp($str) {
		/* FIXME This breaks when [] characters are passed. */
		return preg_quote($str);
	}

}

?>
