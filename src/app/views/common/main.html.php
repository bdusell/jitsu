<?php ini_set('default_charset', 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= html($title) ?> | <?= html(config::site_name()) ?></title>
<?php if(isset($base)): ?>
    <base href="<?= html($base) ?>" />
<?php endif; ?>
<?php if(isset($favicon)): ?>
    <link rel="icon" type="image/<?= FileUtil::get_extension($favicon) ?>" href="<?= html($favicon) ?>" />
<?php endif; ?>
<?php if(isset($stylesheets)): ?>
<?php   foreach($stylesheets as $css): ?>
    <link rel="stylesheet" type="text/css" href="<?= htmlattr($css) ?>" />
<?php   endforeach; ?>
<?php endif; ?>
<?php if(isset($scripts)): ?>
<?php   foreach($scripts as $js): ?>
    <script type="text/javascript" src="<?= htmlattr($js) ?>" defer></script>
<?php   endforeach; ?>
<?php endif; ?>
  </head>
  <body>
<?php Util::template('common/header.html.php', $vars); ?>
    <main class="container">
<?php Util::template("$body.html.php", $vars); ?>
    </main>
  </body>
</html>
