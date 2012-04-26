(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.routers.Resource = (function(_super) {

    __extends(Resource, _super);

    function Resource() {
      Resource.__super__.constructor.apply(this, arguments);
    }

    Resource.prototype.routes = {
      ':id': 'noIndex',
      ':id/': 'noIndex',
      ':id/:index': 'indexChange'
    };

    Resource.prototype.noIndex = function(id) {
      return arcs.trigger('arcs:indexChange', 0, {
        noNavigate: true,
        replace: true
      });
    };

    Resource.prototype.indexChange = function(id, index) {
      if (_.isNumeric(index)) index -= 1;
      return arcs.trigger('arcs:indexChange', index, {
        noNavigate: true
      });
    };

    return Resource;

  })(Backbone.Router);

}).call(this);
