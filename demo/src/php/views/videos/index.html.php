<h1>Videos</h1>
<div class="row">
<?php
  foreach($videos as $video):
?>
  <div class="col-xs-6 col-md-2">
    <a class="thumbnail video-thumbnail" href="videos/<?= html($video->id) ?>">
      <div class="caption">
        <?= html($video->name) ?>
      </div>
    </a>
  </div>
<?php
  endforeach;
?>
  <div class="col-xs-6 col-md-2">
    <a class="thumbnail video-thumbnail" href="videos/new">
      <div class="caption">
        <span class="text-info">
          + Add New Video
        </span>
      </div>
    </a>
  </div>
</div>
