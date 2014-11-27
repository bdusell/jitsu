<p>Your path is: <code><?= html($path) ?></code></p>
<pre><?= html(repr($_SERVER)) ?></pre>
<pre><?= html(repr(Request::form())) ?></pre>
<?php setlocale(LC_MONETARY, 'en_US.utf8'); ?>
<pre><?= html(repr(money_format('%n', 12345678))) ?></pre>
<pre><?= html(repr(number_format(12345678))) ?></pre>
<pre><?= html(repr(number_format(12345678, 0))) ?></pre>
<pre><?= html(repr(number_format(12345678, 2))) ?></pre>
