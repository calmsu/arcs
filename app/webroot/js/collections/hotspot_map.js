(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.HotspotMap = (function(_super) {

    __extends(HotspotMap, _super);

    function HotspotMap() {
      HotspotMap.__super__.constructor.apply(this, arguments);
    }

    HotspotMap.prototype.model = arcs.models.Hotspot;

    HotspotMap.prototype.url = function() {
      return arcs.baseURL + "resources/hotspots/" + arcs.resource.id;
    };

    return HotspotMap;

  })(Backbone.Collection);

}).call(this);
