<?php

namespace phrame;

interface Navigator {

	public function not_found($method, $path);
	public function bad_method($method, $path, $methods);
	public function permanent_redirect($from, $to);
	public function handle_exception($method, $path, $e);
}

?>
