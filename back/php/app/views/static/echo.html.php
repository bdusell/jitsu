<p>Your path is: <code><?= html($path) ?></code></p>
<pre><?= html(repr($_SERVER)) ?></pre>
<pre><?= html(repr(Request::form())) ?></pre>
<pre><?= html(repr(StringUtil::split_camel_case('XMLHttpRequest'))) ?></pre>
