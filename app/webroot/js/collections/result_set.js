var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.collections.ResultSet = (function(_super) {

  __extends(ResultSet, _super);

  function ResultSet() {
    ResultSet.__super__.constructor.apply(this, arguments);
  }

  ResultSet.prototype.model = arcs.models.Resource;

  ResultSet.prototype.url = function() {
    return arcs.baseURL + 'search/';
  };

  ResultSet.prototype.parse = function(response) {
    var r, resources, user_name, _i, _len;
    resources = [];
    for (_i = 0, _len = response.length; _i < _len; _i++) {
      r = response[_i];
      user_name = r.User.name;
      r = r.Resource;
      r.user_name = user_name;
      if (r.modified === r.created) r.modified = null;
      resources.push(r);
    }
    return resources;
  };

  return ResultSet;

})(Backbone.Collection);
