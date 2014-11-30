<?php
config::is_production(false);
config::base_url($_SERVER['REQUEST_URI']);
config::path('videos-dev');
?>
