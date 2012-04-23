(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.MetadataContainer = (function(_super) {

    __extends(MetadataContainer, _super);

    function MetadataContainer() {
      MetadataContainer.__super__.constructor.apply(this, arguments);
    }

    MetadataContainer.prototype.url = function() {
      return arcs.baseURL + 'resources/metadata/' + this.id;
    };

    return MetadataContainer;

  })(Backbone.Model);

}).call(this);
