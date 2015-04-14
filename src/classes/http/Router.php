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
		$this->routes[] = array(strtoupper($method), $pattern, $callback);
		return $this;
	}

	public function bad_method($callback) {
		$this->callbacks[__FUNCTION__] = $callback;
		return $this;
	}

	public function not_found($callback) {
		$this->callbacks[__FUNCTION__] = $callback;
		return $this;
	}

	public function permanent_redirect($callback) {
		$this->callbacks[__FUNCTION__] = $callback;
		return $this;
	}

	public function route($response, $method, $path) {
		return (new _Router_impl(array(
			'response' => $response,
			'method' => $method,
			'path' => $path,
			'callbacks' => $this->callbacks,
			'routes' => $this->routes
		)))->route();
	}
}

class _Router_impl {

	private $response;
	private $method;
	private $path;
	private $callbacks;
	private $routes;

	private $matched_methods;

	public function __construct($options) {
		foreach($options as $name => $value) {
			$this->$name = $value;
		}
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

	private function call($name) {
		$args = func_get_args();
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
				$this->call('permanent_redirect', $this->path . '/');
				return true;
			} else {
				if(strcasecmp($method, $this->method) === 0) {
					array_shift($matches);
					$decoded_matches = array();
					foreach($matches as $m) {
						$decoded_matches[] = urldecode($m);
					}
					call_user_func_array($func, $decoded_matches);
					return true;
				} else {
					$this->matched_methods[$method] = true;
				}
			}
		}
		return false;
	}

	private static function pattern_to_regex($pat, &$trailing_slash) {
		$trailing_slash = false;
		$regex = preg_replace_callback(
			'#(:[A-Za-z_]+)|(\\*[A-Za-z_]+)|(/$)|(\\()|(\\))|(.+?)#',
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
					return preg_quote($matches[6], '#');
				}
			},
			$pat
		);
		return '#^' . $regex . '$#';
	}
}

?>
