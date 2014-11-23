<h1>Videos</h1>
<ul>
<?php foreach($videos as $video): ?>
  <li><a href="videos/<?= htmlattr($video->id) ?>"><?= html($video->id) ?></a></li>
<?php endforeach; ?>
  <li><a href="videos/new">New Video</a></li>
</ul>
<ul>
  <li><a href="">Home</a></li>
</ul>
