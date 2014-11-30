<?php

class StaticPagesController {

	public static function home() {
		Pages::page('static/home', array('title' => 'Home'));
	}

	public static function echo_($path) {
		Pages::page('static/echo', array('title' => 'Echo', 'path' => $path));
	}
}

?>
