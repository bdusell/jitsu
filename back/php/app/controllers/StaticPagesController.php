<?php

class StaticPagesController {

	public static function home() {
		AppHelper::page('static/home', array('title' => 'Home'));
	}
}

?>
