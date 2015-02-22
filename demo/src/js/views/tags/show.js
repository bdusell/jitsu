var $ = require('jquery');
var Backbone = require('../../shim/backbone');

module.exports = Backbone.View.extend({
  tagName: 'li',
  render: function() {
    this.$el.text(this.model.get('value'))
      .append($('<span>').addClass('x-button'));
    return this;
  },
  events: {
    'click .x-button': function() {
      this.model.destroy();
    }
  }
});
