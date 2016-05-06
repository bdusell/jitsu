<h1>500: Internal Server Error</h1>
<p>There was an unexpected error.</p>
<?php if(isset($sql_error)): ?>
<pre><?= html($sql_error) ?></pre>
<?php endif; ?>
<?php if(isset($stack_trace)): ?>
<pre><?= html($stack_trace) ?></pre>
<?php endif; ?>
