<h1>Videos</h1>
<div class="row">
<?php
  foreach($videos as $video):
?>
  <div class="col-xs-6 col-md-2">
    <a class="thumbnail" href="videos/<?= html($video->id) ?>">
      <div class="caption">
        <h2><?= html($video->name) ?></h2>
        <p><?= html($video->id) ?></p>
      </div>
    </a>
  </div>
<?php
  endforeach;
?>
  <div class="col-xs-6 col-md-2">
    <a class="thumbnail" href="videos/new">
      <div class="caption">
        <h2>Add New Video</h2>
        <p>+</p>
      </div>
    </a>
  </div>
</div>
