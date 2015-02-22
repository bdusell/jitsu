var Backbone = require('../shim/backbone');
var Tag = require('../models/tag');

module.exports = Backbone.Collection.extend({
  model: Tag,
  initialize: function(models, options) {
    this.videoId = options.videoId;
    this.on('add', function(model) {
      model.videoId = this.videoId;
    });
  },
  url: function() {
    return 'videos/' + this.videoId + '/tags/';
  }
});
