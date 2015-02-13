<?php

namespace phrame\http;

class Router {

	private $routes = array();
	private $callbacks = array();

	public function __construct($filename = null) {
		if($filename !== null) {
			$this->read($filename);
		}
	}

	public function read($filename) {
		$router = $this;
		include $filename;
		return $this;
	}

	public function map($method, $pattern, $callback) {
		$routes[] = func_get_args();
		return $this;
	}

	public function internal_error($callback) {
		$this->callbacks[__FUNCTION__] = $callback;
	}

	public function bad_method($callback) {
		$this->callbacks[__FUNCTION__] = $callback;
	}

	public function not_found($callback) {
		$this->callbacks[__FUNCTION__] = $callback;
	}

	public function route($response, $method, $path) {
		$impl = new _Router_impl(
			$response,
			$method,
			$path,
			$this->callbacks
		);
		foreach($this->routes as $route) {
			call_user_func_array(array($impl, 'map'), $route);
		}
		return $impl->route();
	}
}

class _Router_impl {

	private $response;
	private $method;
	private $path;
	private $callbacks;

	private $routes = array();
	private $matched_methods;

	public function __construct($response, $method, $path, $callbacks) {
		$this->response = $response;
		$this->method = $method;
		$this->path = $path;
		$this->callbacks = $callbacks;
	}

	public final function route() {
		$this->matched_methods = array();
		foreach($this->routes as $route) {
			list($method, $path, $callback) = $route;
			if($this->try_route($method, $path, $callback)) return true;
		}
		if($this->matched_methods) {
			$this->call(
				'bad_method',
				array_keys($this->matched_methods),
				$this->method,
				$this->path
			);
		} else {
			$this->call('not_found', $this->method, $this->path);
		}
		return false;
	}

	public final function map($method, $pat, $callback) {
		$this->routes[] = array(strtoupper($method), $pat, $callback);
	}

	private function call($name) {
		$args = func_get_args();
		// TODO
		//$args[0] = $this->response;
		array_shift($args);
		return call_user_func_array(
			$this->callbacks[$name],
			$args
		);
	}

	private function try_route($method, $pat, $func) {
		$regex = self::pattern_to_regex($pat, $trailing_slash);
		if(preg_match($regex, $this->path, $matches)) {
			if($trailing_slash && substr($this->path, -1) !== '/') {
				$this->response->redirect($this->path . '/', 301);
			} else {
				if(strcasecmp($method, $this->method) == 0) {
					// TODO
					//$matches[0] = $this->response;
					array_shift($matches);
					call_user_func_array($func, $matches);
					return true;
				} else {
					$this->matched_methods[$method] = true;
				}
			}
		}
		return false;
	}

	public static function pattern_to_regex($pat, &$trailing_slash) {
		$trailing_slash = false;
		$regex = preg_replace_callback(
			'!(:[A-Za-z_]+)|(\\*[A-Za-z_]+)|(/$)|(\\()|(\\))|(.+?)!',
			function($matches) use(&$trailing_slash) {
				if($matches[1] !== '') {
					return '([^/]+)';
				} elseif($matches[2] !== '') {
					return '(.*?)';
				} elseif($matches[3] !== '') {
					$trailing_slash = true;
					return '/?';
				} elseif($matches[4] !== '') {
					return '(?:';
				} elseif($matches[5] !== '') {
					return ')?';
				} else {
					return preg_quote($matches[6], '|');
				}
			},
			$pat
		);
		return "|^$regex$|";
	}
}

?>
