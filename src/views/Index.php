<?php

class Index extends View {

	/* Variables to store parameters. */

	public function __construct() {
		/* Store parameters in variables. */
	}

	public function scripts() {
		return array_merge(
			parent::scripts(),
			array('js/index.js')
		);
	}

}

?>
