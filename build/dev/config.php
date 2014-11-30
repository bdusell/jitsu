<?php
config::is_production(false);
if(isset($_SERVER['REQUEST_URI'])) {
	config::base_url($_SERVER['REQUEST_URI']);
}
config::path('videos-dev');
?>
