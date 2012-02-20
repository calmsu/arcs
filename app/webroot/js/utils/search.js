
arcs.utils.Search = (function() {

  function Search(options) {
    var defaults,
      _this = this;
    defaults = {
      container: null,
      query: '',
      useParms: true,
      run: true,
      success: function() {},
      error: function() {}
    };
    if (options.facets != null) this.facets = options.facets;
    this.options = _.extend(defaults, options);
    this.query = this.options.query;
    this.results = new arcs.collections.ResultSet;
    this.vs = VS.init({
      container: this.options.container,
      query: this.options.query,
      callbacks: {
        search: function(query, searchCollection) {
          return _this.run(searchCollection.toJSON());
        },
        facetMatches: function(callback) {
          return callback(_.keys(_this.facets));
        },
        valueMatches: function(facet, searchTerm, callback) {
          var val;
          val = _this.facets[facet];
          if (typeof val === 'function') {
            _this.facets[facet] = val();
            return callback(_this.facets[facet]);
          } else {
            return callback(val);
          }
        }
      }
    });
    if (this.options.run) this.run();
  }

  Search.prototype.facets = {
    access: ['public', 'private'],
    filetype: function() {
      var k, v, _ref, _results;
      _ref = arcs.utils.mime.types();
      _results = [];
      for (k in _ref) {
        v = _ref[k];
        _results.push({
          value: k,
          label: v
        });
      }
      return _results;
    },
    filename: [],
    id: [],
    sha: [],
    title: function() {
      return arcs.utils.complete.title();
    },
    user: function() {
      return arcs.utils.complete.user();
    },
    tag: function() {
      return arcs.utils.complete.tag();
    },
    collection: [],
    created: function() {
      return arcs.utils.complete.created();
    },
    uploaded: function() {
      return arcs.utils.complete.created();
    },
    modified: function() {
      return arcs.utils.complete.modified();
    },
    type: function() {
      return arcs.utils.complete.type();
    }
  };

  Search.prototype.run = function(facets, success, error) {
    var n, offset, params, _ref, _ref2;
    if (!(facets != null) && (this.vs != null)) {
      facets = this.vs.searchQuery.toJSON();
    }
    _.each(facets, function(f) {
      return delete f.app;
    });
    if (this.options.useParams) {
      n = (_ref = arcs.utils.params.get('n')) != null ? _ref : 30;
      offset = (_ref2 = arcs.utils.params.get('offset')) != null ? _ref2 : 0;
      params = "?n=" + n + "&offset=" + offset;
    }
    this.results.fetch({
      data: JSON.stringify(facets),
      type: 'POST',
      url: this.results.url() + (params != null ? params : ''),
      contentType: 'application/json',
      success: success || this.options.success,
      error: error || this.options.error
    });
    this.query = this.vs.searchBox.value();
    return this.results;
  };

  return Search;

})();
