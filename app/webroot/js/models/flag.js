(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Flag = (function(_super) {

    __extends(Flag, _super);

    function Flag() {
      Flag.__super__.constructor.apply(this, arguments);
    }

    Flag.prototype.urlRoot = arcs.baseURL + 'flags';

    return Flag;

  })(Backbone.Model);

}).call(this);
