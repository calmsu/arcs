(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Preview = (function(_super) {

    __extends(Preview, _super);

    function Preview() {
      Preview.__super__.constructor.apply(this, arguments);
    }

    Preview.prototype.options = {
      index: 0
    };

    Preview.prototype.initialize = function() {
      if (!$('#modal').length) $('body').append(arcs.tmpl('ui/modal_wrapper'));
      this.el = this.$el = $('#modal');
      this.$el.modal();
      arcs.keys.add('left', false, this.prev, this);
      arcs.keys.add('right', false, this.next, this);
      return this.set(this.options.index);
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

    Preview.prototype.set = function(index) {
      if (index < 0) index = 0;
      if (index >= this.collection.length) index = this.collection.length - 1;
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

    Preview.prototype.render = function() {
      var pageInfo;
      pageInfo = {
        page: this.index + 1,
        count: this.collection.length
      };
      this.$el.html(arcs.tmpl('search/preview', _.extend(pageInfo, this.model.toJSON())));
      return this;
    };

    return Preview;

  })(Backbone.View);

}).call(this);
