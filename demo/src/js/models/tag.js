var Backbone = require('../shim/backbone');

module.exports = Backbone.Model.extend({
  initialize: function(models, options) {
    this.videoId = options.videoId;
  },
  url: function() {
    return 'videos/' + this.videoId + '/tags/' + encodeURIComponent(this.get('value'));
  },
  validate: function() {
    var r;
    if(this.videoId == null) {
      (r = r || {}).videoId = 'missing video ID';
    }
    if(!this.has('value')) {
      (r = r || {}).value = 'missing value';
    }
    return r;
  },
  isNew: function() {
    return false;
  },
  toJSON: function() {
    return {};
  }
});
