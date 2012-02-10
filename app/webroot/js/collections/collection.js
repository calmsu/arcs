var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.collections.Collection = (function(_super) {

  __extends(Collection, _super);

  function Collection() {
    Collection.__super__.constructor.apply(this, arguments);
  }

  Collection.prototype.model = arcs.models.Resource;

  Collection.prototype.parse = function(response) {
    var r, _i, _len;
    for (_i = 0, _len = response.length; _i < _len; _i++) {
      r = response[_i];
      if (r.modified === r.created) r.modified = null;
    }
    return response;
  };

  return Collection;

})(Backbone.Collection);
