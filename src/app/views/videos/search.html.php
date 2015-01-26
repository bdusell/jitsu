<h1>Search Results for "<?= html($tags->join(' ')) ?>"</h1>
<?php if($videos): ?>
<ul>
<?php   foreach($videos as $video): ?>
  <li><a href="videos/<?= htmlattr($video->id) ?>"><?= html($video->name) ?></a></li>
<?php   endforeach; ?>
</ul>
<?php else: ?>
<p>No results.</p>
<?php endif; ?>
