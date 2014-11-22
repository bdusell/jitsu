<?php

class AppRouter extends Router {

	public function routes() {
		$this->map('GET', '', array('StaticPagesController', 'home'));
		$this->map('GET', 'home', array('StaticPagesController', 'home'));
		$this->map('GET', 'lists', array('ListController', 'index'));
		$this->map('POST', 'lists', array('ListController', 'create'));
		$this->map('GET', 'lists/:id', array('ListController', 'show'));
		$this->map('GET', 'cards', array('CardController', 'index'));
		$this->map('POST', 'cards', array('CardController', 'create'));
		$this->map('PUT', 'cards', array('CardController', 'edit'));
		$this->map('GET', 'cards/:id', array('CardController', 'show'));
		$this->map('GET', 'lists/:id/cards', array('ListCardController', 'index'));
		$this->map('POST', 'lists/:id/cards', array('ListCardController', 'create'));
		$this->map('GET', 'lists/:id/cards/:id', array('ListCardController', 'show'));
	}
}

?>
