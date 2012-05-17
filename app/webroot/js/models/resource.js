(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.Resource = (function(_super) {

    __extends(Resource, _super);

    Resource.prototype.defaults = {
      title: '',
      keywords: [],
      annotations: [],
      comments: [],
      metadata: {},
      mime_type: "unknown",
      page: 0,
      preview: false,
      public: false,
      selected: false
    };

    function Resource(attributes) {
      Resource.__super__.constructor.call(this, this.parse(attributes));
    }

    Resource.prototype.url = function() {
      return arcs.baseURL + 'resources/' + this.id;
    };

    Resource.prototype.urlRoot = arcs.baseURL + 'resources';

    Resource.prototype.parse = function(r) {
      var k, m, v, _i, _j, _len, _len2, _ref, _ref2, _ref3;
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
        if (r.Keyword != null) {
          r.keywords = (function() {
            var _i, _len, _ref2, _results;
            _ref2 = r.Keyword;
            _results = [];
            for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
              k = _ref2[_i];
              _results.push(k.keyword);
            }
            return _results;
          })();
          delete r.Keyword;
        }
        if (r.Comment != null) {
          r.comments = r.Comment;
          delete r.Comment;
        }
        if (r.Flag != null) {
          r.flags = r.Flag;
          delete r.Flag;
        }
        if (r.Membership != null) {
          r.memberships = {};
          _ref2 = r.Membership;
          for (_i = 0, _len = _ref2.length; _i < _len; _i++) {
            m = _ref2[_i];
            r.memberships[m.collection_id] = parseInt(m.page);
          }
          delete r.Membership;
        }
        if (r.Annotation != null) {
          r.annotations = r.Annotation;
          delete r.Annotation;
        }
        if (r.Metadatum != null) {
          r.metadata = new arcs.models.MetadataContainer;
          r.metadata.id = r.id;
          _ref3 = r.Metadatum;
          for (_j = 0, _len2 = _ref3.length; _j < _len2; _j++) {
            m = _ref3[_j];
            r.metadata.set(m.attribute, m.value);
          }
          delete r.Metadatum;
        }
        delete r.Resource;
      }
      if (r.modified === r.created) r.modified = false;
      return r;
    };

    return Resource;

  })(Backbone.Model);

}).call(this);
