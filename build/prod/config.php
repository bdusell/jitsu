<?php
config::is_production(true);
config::scheme('http');
config::host('localhost');
config::path('videos');
config::dir(__DIR__);
config::document_root('/var/www/phrame');
?>
