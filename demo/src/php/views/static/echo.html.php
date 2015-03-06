<?php use phrame\RequestUtil as r; ?>
<p>Your path is <code><?= html($path) ?></code>.</p>
<pre><?= html(r::method()) ?> <?= html(r::uri()) ?> <?= html(r::protocol()) ?>

<?php foreach(r::headers() as $name => $value): ?>
<?= html($name) ?>: <?= html($value) ?>

<?php endforeach; ?>

<?= html(r::body()) ?>
</pre>
