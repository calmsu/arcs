(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Bookmark = (function(_super) {

    __extends(Bookmark, _super);

    function Bookmark() {
      Bookmark.__super__.constructor.apply(this, arguments);
    }

    Bookmark.prototype.urlRoot = arcs.baseURL + 'bookmarks';

    return Bookmark;

  })(Backbone.Model);

}).call(this);
