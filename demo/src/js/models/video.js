var Backbone = require('../shim/backbone');
var TagCollection = require('../collections/tag_collection');

module.exports = Backbone.Model.extend({
  initialize: function(attrs, options) {
    this.tags = new TagCollection(null, { videoId: this.id });
  }
});
