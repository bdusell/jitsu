var $ = require('jquery');
var Backbone = require('../../shim/backbone');
var _ = require('underscore');
var TagView = require('./show');

module.exports = Backbone.View.extend({
  initialize: function() {
    this._$container = null;
    this._subviews = [];
    this.listenTo(this.collection, 'add', function(model) {
      if(this._$container == null) {
        this._renderContainer();
      }
      this._appendView(model);
    });
    this.listenTo(this.collection, 'remove', function(model) {
      // Linear search ain't so bad
      for(var i = 0, n = this._subviews.length; i < n; ++i) {
        var view = this._subviews[i];
        if(view.model === model) {
          view.remove();
          this._subviews.splice(i, 1);
          break;
        }
      }
      if(this.collection.isEmpty()) {
        this._renderEmpty();
      }
    });
  },
  render: function() {
    var self = this;
    while(this._subviews.length > 0) {
      this._subviews.pop().remove();
    }
    if(this.collection.isEmpty()) {
      this._renderEmpty();
    } else {
      this._renderContainer();
      this.collection.each(function(model) {
        self._appendView(model);
      });
    }
    return this;
  },
  remove: function() {
    _.each(this._subviews, function(view) {
      view.remove();
    });
    return Backbone.View.prototype.remove.apply(this, arguments);
  },
  _renderContainer: function() {
    this._$container = $('<ul>').addClass('tag-list').appendTo(this.$el.empty());
  },
  _renderEmpty: function() {
    this.$el.html('No tags.');
    this._$container = null;
  },
  _appendView: function(model) {
    var view = new TagView({ model: model });
    this._subviews.push(view);
    view.render();
    this._$container.append(view.el);
  }
});
