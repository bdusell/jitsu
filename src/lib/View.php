<?php

class View {

	public static function template($name, $vars = null) {
		if(!is_null($vars)) extract($vars);
		include "../src/app/templates/$name.php";
	}
}

?>
