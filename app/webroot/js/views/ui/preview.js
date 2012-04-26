(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Preview = (function(_super) {

    __extends(Preview, _super);

    function Preview() {
      Preview.__super__.constructor.apply(this, arguments);
    }

    Preview.prototype.options = {
      index: 0,
      template: 'search/preview'
    };

    Preview.prototype.initialize = function() {
      if (!$('#modal').length) $('body').append(arcs.tmpl('ui/modal_wrapper'));
      this.el = this.$el = $('#modal');
      this.$el.modal();
      arcs.keys.map(this, {
        left: this.prev,
        right: this.next
      });
      return this.set(this.options.index, true);
    };

    Preview.prototype.events = {
      'click #prev-btn': 'prev',
      'click #next-btn': 'next'
    };

    Preview.prototype.prev = function() {
      return this.set(this.index - 1);
    };

    Preview.prototype.next = function() {
      return this.set(this.index + 1);
    };

    Preview.prototype.set = function(index, force) {
      if (force == null) force = false;
      if (!((0 <= index && index < this.collection.length) || force)) return;
      this.model = this.collection.at(index);
      this.index = index;
      this._preloadNext();
      return this.render();
    };

    Preview.prototype._preloadNext = function() {
      if (this.index + 1 < this.collection.length) {
        return arcs.preload(this.collection.at(this.index + 1).get('url'));
      }
    };

    Preview.prototype.remove = function() {
      this.$el.modal('hide');
      Preview.__super__.remove.call(this);
      return this.undelegateEvents();
    };

    Preview.prototype.render = function() {
      var pageInfo;
      pageInfo = {
        page: this.index + 1,
        count: this.collection.length
      };
      this.$el.html(arcs.tmpl(this.options.template, _.extend(this.model.toJSON(), pageInfo)));
      return this;
    };

    return Preview;

  })(Backbone.View);

}).call(this);
