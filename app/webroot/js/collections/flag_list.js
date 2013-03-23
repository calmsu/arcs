(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.FlagList = (function(_super) {

    __extends(FlagList, _super);

    function FlagList() {
      FlagList.__super__.constructor.apply(this, arguments);
    }

    FlagList.prototype.model = arcs.models.Flag;

    FlagList.prototype.url = function() {
      return arcs.baseURL + "resources/flags/" + arcs.resource.id;
    };

    FlagList.prototype.parse = function(response) {
      var r, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = response.length; _i < _len; _i++) {
        r = response[_i];
        _results.push(r.Flag);
      }
      return _results;
    };

    return FlagList;

  })(Backbone.Collection);

}).call(this);
