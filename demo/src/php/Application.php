<?php

namespace DemoApp;

class Application extends \jitsu\app\Application {

	public function initialize() {
		$this->set_namespace('DemoApp');
		$this->get('(home)',          'Pages::home');
		$this->get('videos/',         'Videos::read_many');
		$this->get('videos/new',      'Videos::new_form');
		$this->post('videos/',        'Videos::create');
		$this->get('videos/search',   'Videos::search');
		$this->get('videos/:id',      'Videos::read');
		$this->get('videos/:id/edit', 'Videos::edit_form');
		$this->put('videos/:id',      'Videos::update');
		$this->delete('videos/:id',   'Videos::delete');
		$this->put('videos/:id/tags/:value', 'Tags::create');
		$this->delete('videos/:id/tags/:value', 'Tags::delete');
		$this->path('echo/*path',     'Pages::echo_request');
		$this->bad_method('Errors::bad_method');
		$this->not_found('Errors::not_found');
		$this->error('Errors::internal_error');
	}

	public static function config() {
		return new \jitsu\app\SiteConfig(
			__DIR__ . '/config.php',
			'./config.php'
		);
	}
}

?>
