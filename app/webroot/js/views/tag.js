(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Tag = (function(_super) {

    __extends(Tag, _super);

    function Tag() {
      this.keydownDelegate = __bind(this.keydownDelegate, this);
      Tag.__super__.constructor.apply(this, arguments);
    }

    Tag.prototype.events = {
      'keydown #new-tag': 'keydownDelegate'
    };

    Tag.prototype.initialize = function() {
      var _this = this;
      this.collection = new arcs.collections.TagList;
      arcs.bind('resourceChange', function() {
        return _this.update();
      });
      _.bindAll(this, 'render');
      this.collection.bind('add', this.render, this);
      this.collection.bind('remove', this.render, this);
      arcs.utils.autocomplete({
        sel: '#new-tag',
        source: arcs.utils.complete.tag()
      });
      return this.update();
    };

    Tag.prototype.keydownDelegate = function(e) {
      if (e.keyCode === 13) {
        this.saveTag();
        e.preventDefault();
        return false;
      }
    };

    Tag.prototype.saveTag = function() {
      var $input, tag;
      $input = this.$el.find('input#new-tag');
      tag = new arcs.models.Tag({
        resource_id: arcs.resource.id,
        tag: $input.val()
      });
      $input.val('');
      tag.save();
      return this.collection.add(tag);
    };

    Tag.prototype.update = function() {
      var _this = this;
      return this.collection.fetch({
        success: function() {
          return _this.render();
        }
      });
    };

    Tag.prototype.render = function() {
      var $tags;
      $tags = $('#tags-wrapper');
      $tags.html(arcs.tmpl('resource/tags', {
        tags: this.collection.toJSON()
      }));
      return this;
    };

    return Tag;

  })(Backbone.View);

}).call(this);
