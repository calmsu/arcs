var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.models.Tag = (function(_super) {

  __extends(Tag, _super);

  function Tag() {
    Tag.__super__.constructor.apply(this, arguments);
  }

  Tag.prototype.urlRoot = arcs.baseURL + 'tags';

  Tag.prototype.validate = function(attrs) {
    var tags;
    if (arcs.tagView != null) {
      tags = arcs.tagView.collection.pluck('tag');
      tags.push(attrs.tag);
      if (_.uniq(tags).length !== tags.length) return arcs.log('non unique');
    }
  };

  return Tag;

})(Backbone.Model);
