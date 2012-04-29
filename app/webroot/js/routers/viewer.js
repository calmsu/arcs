(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.routers.Viewer = (function(_super) {

    __extends(Viewer, _super);

    function Viewer() {
      Viewer.__super__.constructor.apply(this, arguments);
    }

    Viewer.prototype.routes = {
      ':id': 'noIndex',
      ':id/': 'noIndex',
      ':id/:index': 'indexChange'
    };

    Viewer.prototype.noIndex = function(id) {
      return arcs.bus.trigger('indexChange', 0, {
        noNavigate: true,
        replace: true
      });
    };

    Viewer.prototype.indexChange = function(id, index) {
      if (_.isNumeric(index)) index -= 1;
      return arcs.bus.trigger('indexChange', index, {
        noNavigate: true
      });
    };

    return Viewer;

  })(Backbone.Router);

}).call(this);
