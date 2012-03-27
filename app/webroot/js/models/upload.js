(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Upload = (function(_super) {

    __extends(Upload, _super);

    function Upload() {
      Upload.__super__.constructor.apply(this, arguments);
    }

    Upload.prototype.defaults = {
      name: null,
      sha: null,
      identifier: null,
      progress: 0,
      size: 0,
      error: 0,
      type: 'unknown',
      title: null,
      lastModifiedDate: null
    };

    return Upload;

  })(Backbone.Model);

}).call(this);
