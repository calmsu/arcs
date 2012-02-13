
arcs.utils.Search = (function() {

  function Search(options) {
    var defaults,
      _this = this;
    defaults = {
      container: null,
      query: '',
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
            return callback(val());
          } else {
            return callback(val);
          }
        }
      }
    });
    this.run();
  }

  Search.prototype.facets = {
    filetype: function() {
      return arcs.utils.mime.types();
    },
    filename: [],
    sha: [],
    title: function() {
      return arcs.utils.complete.titles();
    },
    user: function() {
      return arcs.utils.complete.users();
    },
    tag: function() {
      return arcs.utils.complete.tags();
    },
    collection: [],
    date: []
  };

  Search.prototype.run = function(facets, success, error) {
    if (!(facets != null) && (this.vs != null)) {
      facets = this.vs.searchQuery.toJSON();
    }
    _.each(facets, function(f) {
      return delete f.app;
    });
    this.results.fetch({
      data: JSON.stringify(facets),
      type: 'POST',
      contentType: 'application/json',
      success: success || this.options.success,
      error: error || this.options.error
    });
    this.query = this.vs.searchBox.value();
    return this.results;
  };

  return Search;

})();
