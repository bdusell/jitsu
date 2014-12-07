<h1>Videos</h1>
<div class="row">
<?php
  foreach($videos as $video):
?>
  <div class="col-xs-6 col-md-2">
    <div class="video-cell-container">
      <div class="video-cell">
        <a href="videos/<?= html($video->id) ?>">
          <h2><?= html($video->name) ?></h2>
          <p><?= html($video->id) ?></p>
        </a>
      </div>
    </div>
  </div>
<?php
  endforeach;
?>
  <div class="col-xs-6 col-md-2">
    <div class="video-cell-container">
      <div class="video-cell">
        <a href="videos/new">
          <h2>Add New Video</h2>
        </a>
      </div>
    </div>
  </div>
</div>
