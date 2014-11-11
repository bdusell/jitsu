<?php

class IndexController {

	public static function show() {
		AppHelper::page('index/show', array('title' => 'Home'));
	}
}

?>
