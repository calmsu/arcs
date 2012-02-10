var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
arcs.utils.Search = (function() {
  function Search(options) {
    var defaults;
    defaults = {
      container: null,
      query: '',
      success: function() {},
      error: function() {}
    };
    if (options.facets != null) {
      this.facets = options.facets;
    }
    this.options = _.extend(defaults, options);
    this.results = new arcs.collections.ResultSet;
    this.vs = VS.init({
      container: this.options.container,
      query: this.options.query,
      callbacks: {
        search: __bind(function(query, searchCollection) {
          return this.run(searchCollection.toJSON());
        }, this),
        facetMatches: __bind(function(callback) {
          return callback(_.keys(this.facets));
        }, this),
        valueMatches: __bind(function(facet, searchTerm, callback) {
          var val;
          val = this.facets[facet];
          if (typeof val === 'function') {
            return callback(val());
          } else {
            return callback(val);
          }
        }, this)
      }
    });
    this.run();
  }
  Search.prototype.facets = {
    filetype: arcs.utils.mime.types,
    filename: [],
    sha: [],
    title: arcs.utils.complete.titles,
    user: arcs.utils.complete.users,
    tag: arcs.utils.complete.tags,
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
    return this.results;
  };
  return Search;
})();