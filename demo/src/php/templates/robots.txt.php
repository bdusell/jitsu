<?php $config = \DemoApp\Application::config(); ?>
User-agent: *
Disallow: <?= $config->make_path('videos/search/') ?>

Disallow: <?= $config->make_path('echo/') ?>

