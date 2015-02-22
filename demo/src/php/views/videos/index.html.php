<h1>Videos</h1>
<div class="row">
<?php
  foreach($videos as $video):
    \phrame\Util::template(__DIR__ . '/_item.html.php', array('video' => $video));
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
