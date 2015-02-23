var $ = require('jquery');
var Backbone = require('../../shim/backbone');

module.exports = Backbone.View.extend({
  tagName: 'li',
  initialize: function() {
    this.listenTo(this.model, 'change:value', this.render);
  },
  render: function() {
    this.$el.empty().text(this.model.get('value'))
      .append($('<span>').addClass('x-button'));
    return this;
  },
  events: {
    'click .x-button': function() {
      this.model.destroy();
    }
  }
});
