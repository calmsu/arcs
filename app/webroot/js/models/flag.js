(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Flag = (function(_super) {

    __extends(Flag, _super);

    Flag.prototype.urlRoot = arcs.baseURL + 'flags';

    function Flag(attributes) {
      Flag.__super__.constructor.call(this, this.parse(attributes));
    }

    Flag.prototype.parse = function(f) {
      var k, v, _ref;
      if (f.Flag != null) {
        _ref = f.Flag;
        for (k in _ref) {
          v = _ref[k];
          f[k] = v;
          delete f.Flag;
        }
        if (f.User != null) {
          f.user = f.User;
          delete f.User;
        }
        if (f.Resource != null) {
          f.resource = f.Resource;
          delete f.Resource;
        }
      }
      return f;
    };

    return Flag;

  })(Backbone.Model);

}).call(this);
