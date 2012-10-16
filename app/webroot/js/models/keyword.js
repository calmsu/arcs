(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Keyword = (function(_super) {

    __extends(Keyword, _super);

    function Keyword() {
      Keyword.__super__.constructor.apply(this, arguments);
    }

    Keyword.prototype.url = function() {
      if (this.isNew()) return arcs.baseURL + 'keywords';
      return arcs.baseURL + ("keywords/" + this.id);
    };

    return Keyword;

  })(Backbone.Model);

}).call(this);
