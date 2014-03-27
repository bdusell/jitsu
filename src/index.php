<?php
	/* All main page requests redirect here. Display the view named by the
	'page' parameter. */
	include 'init.php';
	View::page(Param::get('page', 'index'));
?>
