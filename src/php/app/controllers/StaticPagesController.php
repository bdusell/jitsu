<?php

class StaticPagesController {

	public static function home() {
		AppHelper::page('static/home', array('title' => 'Home'));
	}

	public static function echo_($path) {
		AppHelper::page('static/echo', array('title' => 'Echo', 'path' => $path));
	}
}

?>
