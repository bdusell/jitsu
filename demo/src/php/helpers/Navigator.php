<?php

class Navigator implements phrame\Navigator {

	public function not_found($method, $path) {
		Pages::error(404, array(
			'method' => $method,
			'path' => $path
		));
	}

	public function bad_method($method, $path, $methods) {
		Response::header('Allow', implode(', ', $methods));
		Pages::error(405, array(
			'method' => $method,
			'path' => $path,
			'methods' => $methods
		));
	}

	public function permanent_redirect($from, $to) {
		header('Location: ' . Config::make_url($to), true, 301);
		exit(0); // important
	}
}

?>
