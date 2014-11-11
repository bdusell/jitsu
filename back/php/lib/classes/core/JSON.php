<?php

class JSON {

	public static function encode($obj, $pretty = false) {
		return json_encode($obj, $pretty ? JSON_PRETTY_PRINT : 0);
	}
}

?>
