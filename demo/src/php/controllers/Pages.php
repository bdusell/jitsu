<?php

namespace DemoApp;

class Pages {

	public static function home($data) {
		Util::wrap($data, function($data) {
			Util::page($data, 'static/home', array('title' => 'Home'));
		});
	}

	public static function echo_request($data) {
		Util::wrap($data, function($data) {
			list($path) = $data->parameters;
			$request = $data->request;
			Util::page($data, 'static/echo', array(
				'title' => 'Echo',
				'path' => $path,
				'request' => $request
			));
		});
	}
}

?>
