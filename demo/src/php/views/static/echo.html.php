<?php use phrame\RequestUtil as r; ?>
<p>Your path is <code><?= html($path) ?></code>.</p>

<section>
<h2>HTTP Request</h2>
<pre><?= html(r::method()) ?> <?= html(r::uri()) ?> <?= html(r::protocol()) ?>

<?php foreach(r::headers() as $name => $value): ?>
<?= html($name) ?>: <?= html($value) ?>

<?php endforeach; ?>

<?= html(r::body()) ?>
</pre>
</section>

<section>
<h2>$_SERVER</h2>
<pre><?= html(repr($_SERVER)) ?></pre>
</section>
