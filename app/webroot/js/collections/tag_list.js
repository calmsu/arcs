var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
};
arcs.collections.TagList = (function() {
  __extends(TagList, Backbone.Collection);
  function TagList() {
    TagList.__super__.constructor.apply(this, arguments);
  }
  TagList.prototype.model = arcs.models.Tag;
  TagList.prototype.url = function() {
    return arcs.baseURL + "resources/tags/" + arcs.resource.id;
  };
  TagList.prototype.parse = function(response) {
    var r, _i, _len, _results;
    _results = [];
    for (_i = 0, _len = response.length; _i < _len; _i++) {
      r = response[_i];
      _results.push(r.Tag);
    }
    return _results;
  };
  return TagList;
})();