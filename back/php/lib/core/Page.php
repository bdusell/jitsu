<?php

/* Abstraction of a page. */
abstract class Page {

	private $_params;

	public function __construct() {
		$this->_params = array();
	}

	public function title() {
		return Config::site_name();
	}

	public function base() {
		return null;
	}

	public function favicon() {
		return null;
	}

	public function stylesheets() {
		return array('css/style.css');
	}

	public function scripts() {
		return array('js/main.js');
	}

	public function template() {
		return Util::underscores(preg_replace('/View$/', '', get_class($this)));
	}

	public function set_param($name, $value) {
		$this->_params[$name] = $value;
	}

	public function params() {
		return $this->_params;
	}

	public static function render(/* $subpage, ... */) {
		$args = func_get_args();
		$subpage = array_shift($args);
		$rc = new ReflectionClass(Util::camel_case($subpage));
		$view = $rc->newInstanceArgs($args);
		View::template('main', array(
			'title'       => $view->title(),
			'base'        => $view->base(),
			'favicon'     => $view->favicon(),
			'stylesheets' => $view->stylesheets(),
			'scripts'     => $view->scripts(),
			'template'    => $view->template(),
			'params'      => $view->params()
		));
	}

}

?>
