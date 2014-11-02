<?php

class Serialize {

	public static function json($obj, $pretty = false) {
		return json_encode($obj, $pretty ? JSON_PRETTY_PRINT : 0);
	}
}

?>
