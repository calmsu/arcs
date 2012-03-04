(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.UploadSet = (function(_super) {

    __extends(UploadSet, _super);

    function UploadSet() {
      UploadSet.__super__.constructor.apply(this, arguments);
    }

    UploadSet.prototype.model = arcs.models.Upload;

    UploadSet.prototype.url = function() {
      return arcs.baseURL + 'uploads';
    };

    return UploadSet;

  })(Backbone.Collection);

}).call(this);
