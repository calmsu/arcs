(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.CollectionList = (function(_super) {

    __extends(CollectionList, _super);

    function CollectionList() {
      CollectionList.__super__.constructor.apply(this, arguments);
    }

    CollectionList.prototype.model = arcs.models.Collection;

    return CollectionList;

  })(Backbone.Collection);

}).call(this);
