<?php
	/* All main page requests redirect here. Display the view named by the
	'page' parameter. */
	include '../src/init.php';
	Page::render(Param::get('page', 'index'));
?>
