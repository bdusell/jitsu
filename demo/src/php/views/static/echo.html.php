<p>Your path is <code><?= html($path) ?></code>.</p>

<section>
<h2>HTTP Request</h2>
<pre><?= html($request->method()) ?> <?= html($request->uri()) ?> <?= html($request->protocol()) ?>

<?php foreach($request->headers() as $name => $value): ?>
<?= html($name) ?>: <?= html($value) ?>

<?php endforeach; ?>

<?= html($request->body()) ?>
</pre>
</section>
