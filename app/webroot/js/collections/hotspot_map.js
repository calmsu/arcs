var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
};
arcs.collections.HotspotMap = (function() {
  __extends(HotspotMap, Backbone.Collection);
  function HotspotMap() {
    HotspotMap.__super__.constructor.apply(this, arguments);
  }
  HotspotMap.prototype.model = arcs.models.Hotspot;
  HotspotMap.prototype.url = function() {
    return arcs.baseURL + "resources/hotspots/" + arcs.resource.id;
  };
  HotspotMap.prototype.parse = function(response) {
    var r, _i, _len, _results;
    _results = [];
    for (_i = 0, _len = response.length; _i < _len; _i++) {
      r = response[_i];
      _results.push(r.Hotspot);
    }
    return _results;
  };
  return HotspotMap;
})();