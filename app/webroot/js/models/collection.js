(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Collection = (function(_super) {

    __extends(Collection, _super);

    Collection.prototype.defaults = {
      id: null,
      title: 'Temporary Collection',
      description: '',
      public: false,
      members: []
    };

    function Collection(attributes) {
      Collection.__super__.constructor.call(this, this.parse(attributes));
    }

    Collection.prototype.urlRoot = arcs.baseURL + 'collections/add';

    Collection.prototype.parse = function(c) {
      var k, v, _ref;
      if (c.Collection != null) {
        _ref = c.Collection;
        for (k in _ref) {
          v = _ref[k];
          c[k] = v;
        }
        delete c.Collection;
      }
      return c;
    };

    return Collection;

  })(Backbone.Model);

}).call(this);
