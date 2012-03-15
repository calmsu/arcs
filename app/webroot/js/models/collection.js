(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Collection = (function(_super) {

    __extends(Collection, _super);

    function Collection() {
      Collection.__super__.constructor.apply(this, arguments);
    }

    Collection.prototype.defaults = {
      id: null,
      title: 'Temporary Collection',
      description: '',
      public: false,
      members: []
    };

    Collection.prototype.urlRoot = arcs.baseURL + 'collections/create';

    return Collection;

  })(Backbone.Model);

}).call(this);
