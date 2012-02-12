var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
};
arcs.models.Resource = (function() {
  __extends(Resource, Backbone.Model);
  function Resource() {
    Resource.__super__.constructor.apply(this, arguments);
  }
  Resource.prototype.defaults = {
    id: null,
    mime_type: "image/png",
    modified: null,
    created: null,
    public: false
  };
  Resource.prototype.urlRoot = arcs.baseURL + 'resources';
  Resource.prototype.parse = function(response) {
    if (response.modified = response.created) {
      response.modified = null;
    }
    return response;
  };
  return Resource;
})();