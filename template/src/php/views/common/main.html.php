<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
<?php if(isset($description)): ?>
    <meta name="description" content="<?= htmlattr($description) ?>">
<?php endif; ?>
    <title><?= html($title) ?></title>
    <link rel="stylesheet" href="<?= htmlattr($config->makeUrl('main.css')) ?>">
    <script src="<?= htmlattr($config->makeUrl('main.js')) ?>"></script>
  </head>
  <body>
<?php include $body; ?>
  </body>
</html>
