(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Resource = (function(_super) {

    __extends(Resource, _super);

    function Resource() {
      Resource.__super__.constructor.apply(this, arguments);
    }

    Resource.prototype.defaults = {
      id: null,
      mime_type: "unknown",
      modified: null,
      created: null,
      public: false
    };

    Resource.prototype.urlRoot = arcs.baseURL + 'resources';

    Resource.prototype.parse = function(r) {
      var k, m, t, v, _i, _len, _ref, _ref2;
      if (r.Resource != null) {
        _ref = r.Resource;
        for (k in _ref) {
          v = _ref[k];
          r[k] = v;
        }
        if (r.User != null) {
          r.user = r.User;
          delete r.User;
        }
        if (r.Tag != null) {
          r.tags = (function() {
            var _i, _len, _ref2, _results;
            _ref2 = r.Tag;
            _results = [];
            for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
              t = _ref2[_i];
              _results.push(t.tag);
            }
            return _results;
          })();
          delete r.Tag;
        }
        if (r.Comment != null) {
          r.comments = r.Comment;
          delete r.Comment;
        }
        if (r.Membership != null) {
          _ref2 = r.Membership;
          for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
            m = _ref2[_i];
            r.memberships = m.collection_id;
          }
          delete r.Membership;
        }
        if (r.Hotspot != null) {
          r.hotspots = r.Hotspot;
          delete r.Hotspot;
        }
        delete r.Resource;
      }
      if (r.modified === r.created) r.modified = false;
      r.file_size = arcs.utils.convertBytes(r.file_size);
      return r;
    };

    return Resource;

  })(Backbone.Model);

}).call(this);
