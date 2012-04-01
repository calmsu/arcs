(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.Discussion = (function(_super) {

    __extends(Discussion, _super);

    function Discussion() {
      Discussion.__super__.constructor.apply(this, arguments);
    }

    Discussion.prototype.model = arcs.models.Comment;

    Discussion.prototype.url = function() {
      return arcs.baseURL + "resources/comments/" + arcs.resource.id;
    };

    Discussion.prototype.parse = function(response) {
      var r, _i, _len, _results;
      _results = [];
      for (_i = 0, _len = response.length; _i < _len; _i++) {
        r = response[_i];
        _results.push(_.extend(r.User, r.Comment));
      }
      return _results;
    };

    return Discussion;

  })(Backbone.Collection);

}).call(this);
