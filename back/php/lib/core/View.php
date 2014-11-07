<?php

class View {

	public static function template($name, $vars = null) {
		Util::template("../src/app/templates/$name.php", $vars);
	}
}

?>
