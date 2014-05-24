<?php
/*
@param $title
*/
?>
<?php ini_set('default_charset', 'UTF-8'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $title ?></title>
<?php if(!is_null($base)): ?>
    <base href="<?= Escape::html($base) ?>" />
<?php endif; ?>
<?php if(!is_null($favicon)): ?>
    <link rel="icon" type="image/<?= Util::get_extension($favicon) ?>" href="<?= Escape::html($favicon) ?>" />
<?php endif; ?>
<?php foreach($stylesheets as $css): ?>
    <link rel="stylesheet" type="text/css" href="<?= Escape::html_attr($css) ?>" />
<?php endforeach; ?>
<?php foreach($scripts as $js): ?>
    <script type="text/javascript" src="<?= Escape::html_attr($js) ?>"></script>
<?php endforeach; ?>
  </head>
  <body>
<?php View::template($template, $params); ?>
  </body>
</html>
