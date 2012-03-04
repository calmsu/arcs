(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.routers.Resource = (function(_super) {

    __extends(Resource, _super);

    function Resource() {
      Resource.__super__.constructor.apply(this, arguments);
    }

    Resource.prototype.routes = {
      ':id/:index': 'change'
    };

    Resource.prototype.change = function(id, index) {
      return arcs.log(id, index);
    };

    return Resource;

  })(Backbone.Router);

}).call(this);
