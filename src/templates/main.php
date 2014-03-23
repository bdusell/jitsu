<?php
/*
@param $view The view object
*/
?>
<?php ini_set('default_charset', 'UTF-8'); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?= $view->title(); ?></title>
<?php $_base = $view->base(); ?>
<?php if(!is_null($_base)): ?>
    <base href="<?= Util::html_attr($_base) ?>" />
<?php endif; ?>
<?php $_icon = $view->favicon(); ?>
<?php if(!is_null($_icon)): ?>
    <link rel="icon" type="image/<?= Util::get_extension($_icon) ?>" href="<?= Util::html_attr($_icon) ?>" />
<?php endif; ?>
<?php foreach($view->stylesheets() as $css): ?>
    <link rel="stylesheet" type="text/css" href="<?= Util::html_attr($css) ?>" />
<?php endforeach; ?>
<?php foreach($view->scripts() as $js): ?>
    <script type="text/javascript" src="<?= Util::html_attr($js) ?>"></script>
<?php endforeach; ?>
  </head>
  <body>
<?php $view->content(); ?>
  </body>
</html>
