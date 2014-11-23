<?php

abstract class Router {

	private $method;
	private $path;
	private $routes = array();
	private $matched_methods;

	public function __construct($path) {
		$this->path = $path;
		$this->method = Request::method();
	}

	public abstract function routes();

	protected function not_found($method, $path) {
		call_user_func(array(config::helper(), 'error'), 404, array(
			'method' => $method,
			'path' => $path
		));
	}

	protected function bad_method($method, $path, $methods) {
		call_user_func(array(config::helper(), 'error'), 405, array(
			'method' => $method,
			'path' => $path,
			'methods' => $methods
		));
	}

	public function route() {
		$this->matched_methods = array();
		foreach($this->routes as $route) {
			list($method, $path, $callback) = $route;
			if($this->try_route($method, $path, $callback)) return true;
		}
		if($this->matched_methods) {
			$this->bad_method($this->method, $this->path, $this->matched_methods);
		} else {
			$this->not_found($this->method, $this->path);
		}
		return false;
	}

	public function map($method, $pat, $callback) {
		$this->routes[] = array(strtoupper($method), $pat, $callback);
	}

	private function try_route($method, $pat, $func) {
		$regex = self::pattern_to_regex($pat);
		if(preg_match(Util::p($regex), $this->path, $matches)) {
			if(strcasecmp($method, $this->method) == 0) {
				array_shift($matches);
				call_user_func_array($func, $matches);
				return true;
			} else {
				$this->matched_methods[$method] = true;
			}
		}
		return false;
	}

	public static function pattern_to_regex($pat) {
		$regex = preg_replace_callback('!(:[A-Za-z_]+)|(\\*[A-Za-z_]+)|(/$)|([^:*]+?)!', function($matches) {
			if($matches[1] !== '') {
				return '([^/]+)';
			} elseif($matches[2] !== '') {
				return '(.*?)';
			} elseif($matches[3] !== '') {
				return '/?';
			} else {
				return preg_quote($matches[4], '|');
			}
		}, $pat);
		return "|^$regex$|";
	}
}

?>
