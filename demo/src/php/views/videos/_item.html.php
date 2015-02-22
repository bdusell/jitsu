  <div class="col-xs-6 col-md-2">
    <a class="thumbnail video-thumbnail"
       href="videos/<?= htmlattr(url($video->id)) ?>">
      <div class="caption">
        <?= html($video->name) ?>
      </div>
    </a>
  </div>
