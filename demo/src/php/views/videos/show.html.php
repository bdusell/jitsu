<div class="row">
  <div class="col-md-6">
    <h1><?= html($video->name) ?></h1>
    <div>
      <a href="<?= htmlattr($video->url) ?>"><?= html($video->url) ?></a>
    </div>
  </div>
  <div class="col-md-6">
    <h2>Tags</h2>
    <div class="tag-container">
      <div class="tag-list-view"
        data-id="<?= htmlattr($video->id) ?>"
        data-tags="<?= htmlattr(\jitsu\JSONUtil::encode($tags->to_array())) ?>">
      </div>
      <div class="new-tag">
        <input type="text" placeholder="new tag" size="10">
      </div>
      <div class="tag-edit-button start">edit</div>
      <div class="tag-edit-button done">done</div>
    </div>
  </div>
</div>
<div>
  <a href="videos/" class="btn btn-default">Back</a>
  <a href="videos/<?= htmlattr(url($video->id)) ?>/edit"
     class="btn btn-default">Edit</a>
</div>
