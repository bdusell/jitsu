<h1>500: Internal Server Error</h1>
<p>The server encountered a problem and was unable to fulfill the request.</p>
<?php if(config::show_errors() && isset($message)): ?>
<pre><?= html($message) ?></pre>
<?php endif; ?>
