var Backbone = require('../shim/backbone');

module.exports = Backbone.Collection.extend({
  initialize: function(models, options) {
    this.videoId = options.videoId;
  },
  url: function() {
    return 'videos/' + this.videoId + '/tags/';
  }
});
