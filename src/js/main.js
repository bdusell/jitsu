require('./shim/bootstrap');
var $ = require('jquery');
var TagListView = require('./views/tags/list');
var TagCollection = require('./collections/tag_collection');
var Tag = require('./models/tag');

$(function() {

  $('#search-form').submit(function(event) {
    var query = $('#search-input').val();
    if(!query) {
      event.preventDefault();
    }
  });

  $('.tag-list-view').each(function() {
    var $this = $(this);
    var tags = JSON.parse($this.attr('data-tags'));
    var videoId = $this.attr('data-id');
    var collection = new TagCollection(tags, { videoId: videoId });
    var view = new TagListView({
      el: this,
      collection: collection
    });
    view.render();
    $this.closest('.tag-container').find('.new-tag input').keypress(function(event) {
      if(event.which === 13) {
        var $this = $(this);
        var value = $this.val();
        if(value) {
          collection.add({ value: $this.val() });
          $this.val('');
        }
      }
    });
  });

  $('body').delegate('.tag-edit-button', 'click', function() {
    var $this = $(this);
    var $container = $this.closest('.tag-container');
    $container.toggleClass('editable', $this.hasClass('start'));
    var $input = $container.find('.new-tag input');
    if($input.is(':visible')) {
      $input.focus();
    }
  });
});
