<p>Your path is: <code><?= html($path) ?></code></p>
<pre><?= html(repr($_SERVER)) ?></pre>
<pre><?= html(repr(Request::form())) ?></pre>
<pre><?= html(repr(ini_get('variables_order'))) ?></pre>
