<h1>Video <?= html($video->id) ?></h1>
<ul>
<?php foreach($tags as $tag): ?>
  <li><?= html($tag->value) ?></li>
<?php endforeach; ?>
</ul>
<ul>
  <li><a href="videos/">Back</a></li>
  <li><a href="">Home</a></li>
</ul>
<pre><?= html(repr(VideoModel::fetch($video->id))) ?></pre>
