var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; }, __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
};
arcs.views.Tag = (function() {
  __extends(Tag, Backbone.View);
  function Tag() {
    this.keydownDelegate = __bind(this.keydownDelegate, this);
    Tag.__super__.constructor.apply(this, arguments);
  }
  Tag.prototype.events = {
    'keydown #new-tag': 'keydownDelegate'
  };
  Tag.prototype.initialize = function() {
    this.collection = new arcs.collections.TagList;
    arcs.bind('resourceChange', __bind(function() {
      return this.update();
    }, this));
    _.bindAll(this, 'render');
    this.collection.bind('add', this.render, this);
    this.collection.bind('remove', this.render, this);
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
    $input = this.el.find('input#new-tag');
    tag = new arcs.models.Tag({
      resource_id: arcs.resource.id,
      tag: $input.val()
    });
    $input.val('');
    tag.save();
    return this.collection.add(tag);
  };
  Tag.prototype.update = function() {
    return this.collection.fetch({
      success: __bind(function() {
        return this.render();
      }, this)
    });
  };
  Tag.prototype.render = function() {
    var $tags;
    $tags = $('#tags-wrapper');
    $tags.html(Mustache.render(arcs.templates.tagList, {
      tags: this.collection.toJSON()
    }));
    return this;
  };
  return Tag;
})();