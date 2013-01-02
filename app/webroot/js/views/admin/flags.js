(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  if ((_base = arcs.views).admin == null) _base.admin = {};

  arcs.views.admin.Flags = (function(_super) {

    __extends(Flags, _super);

    function Flags() {
      Flags.__super__.constructor.apply(this, arguments);
    }

    Flags.prototype.initialize = function() {
      this.collection.on('add remove change sync', this.render, this);
      return this.render();
    };

    Flags.prototype.render = function() {
      this.$('#flags').html(arcs.tmpl('admin/flags', {
        flags: this.collection.toJSON()
      }));
      return this;
    };

    return Flags;

  })(Backbone.View);

}).call(this);
