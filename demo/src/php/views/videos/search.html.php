<h1>Search Results for "<?= html($tags->join(' ')) ?>"</h1>
<?php if($videos): ?>
<div class="row">
<?php
  foreach($videos as $video):
    \jitsu\Util::template(__DIR__ . '/_item.html.php', array('video' => $video));
  endforeach;
?>
</div>
<?php else: ?>
<p>No results.</p>
<?php endif; ?>
