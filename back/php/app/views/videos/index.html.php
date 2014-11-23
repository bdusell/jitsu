<h1>Videos</h1>
<ul>
<?php foreach($videos as $video): ?>
  <li><a href="videos/<?= htmlattr($video->id) ?>"><?= html($video->id) ?></a></li>
<?php endforeach; ?>
</ul>
