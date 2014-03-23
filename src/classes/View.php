<?php

/* Access to the views. */
abstract class View {

	public function __construct() {}

	public function title() {
		return Config::site_name();
	}

	public function base() {
		return Config::site_base_url();
	}

	public function favicon() {
		return null;
	}

	public function stylesheets() {
		return array('css/style.css');
	}

	public function scripts() {
		return array('js/jquery.min.js');
	}

	public function content() {
		include 'templates/' . Util::underscores(get_class($this)) . '.php';
	}

	public static function page(/* $subpage, ... */) {
		$args = func_get_args();
		$subpage = array_shift($args);
		$rc = new ReflectionClass(Util::camel_case($subpage));
		$view = $rc->newInstanceArgs($args);
		include 'templates/main.php';
	}

}

?>
