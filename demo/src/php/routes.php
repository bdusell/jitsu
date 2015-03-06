<?php
$router->map('GET',    '(home)', array('StaticPagesController', 'home'));
$router->map('GET',    'videos/', array('VideosController', 'index'));
$router->map('GET',    'videos/new', array('VideosController', 'new_'));
$router->map('POST',   'videos/', array('VideosController', 'create'));
$router->map('GET',    'videos/search', array('VideosController', 'search'));
$router->map('GET',    'videos/:id', array('VideosController', 'show'));
$router->map('GET',    'videos/:id/edit', array('VideosController', 'edit')); // TODO
$router->map('PUT',    'videos/:id', array('VideosController', 'update')); // TODO
$router->map('DELETE', 'videos/:id', array('VideosController', 'delete')); // TODO
$router->map('PUT',    'videos/:id/tags/:value', array('TagsController', 'create'));
$router->map('DELETE', 'videos/:id/tags/:value', array('TagsController', 'delete'));
$router->map('GET',    'echo/*path', array('StaticPagesController', 'echo_'));
$router->map('POST',   'echo/*path', array('StaticPagesController', 'echo_'));
$router->not_found(array('ErrorController', 'not_found'));
$router->bad_method(array('ErrorController', 'bad_method'));
$router->permanent_redirect(array('ErrorController', 'permanent_redirect'));
?>
