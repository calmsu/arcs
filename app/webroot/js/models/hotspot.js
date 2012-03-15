(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Hotspot = (function(_super) {

    __extends(Hotspot, _super);

    function Hotspot() {
      Hotspot.__super__.constructor.apply(this, arguments);
    }

    Hotspot.prototype.urlRoot = arcs.baseURL + 'hotspots';

    Hotspot.prototype.parse = function(response) {
      if (response.Hotspot != null) response = response.Hotspot;
      return response;
    };

    return Hotspot;

  })(Backbone.Model);

}).call(this);
