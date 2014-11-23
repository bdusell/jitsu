<?php

class AppRouter extends Router {

	public function routes() {
		$this->map('GET',    '(home)', array('StaticPagesController', 'home'));
		$this->map('GET',    'videos/', array('VideosController', 'index'));
		$this->map('GET',    'videos/new', array('VideosController', 'new'));
		$this->map('POST',   'videos/', array('VideosController', 'create'));
		$this->map('GET',    'videos/:id', array('VideosController', 'show'));
		$this->map('GET',    'videos/:id/edit', array('VideosController', 'edit'));
		$this->map('PUT',    'videos/:id', array('VideosController', 'update'));
		$this->map('PATCH',  'videos/:id', array('VideosController', 'patch'));
		$this->map('DELETE', 'videos/:id', array('VideosController', 'delete'));
		$this->map('POST',   'videos/:id/tags/', array('TagsController', 'create'));
		$this->map('DELETE', 'videos/:id/tags/:value', array('TagsController', 'delete'));
	}
}

?>
