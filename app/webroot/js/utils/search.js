(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.utils.Search = (function(_super) {

    __extends(Search, _super);

    function Search() {
      Search.__super__.constructor.apply(this, arguments);
    }

    Search.prototype.options = {
      container: null,
      query: '',
      loader: false,
      order: 'modified',
      direction: 'asc',
      page: 1,
      n: 25,
      add: false,
      run: true,
      onSearch: function() {},
      success: function() {},
      error: function() {}
    };

    Search.prototype.facets = {
      id: [],
      sha: [],
      text: [],
      access: ['public', 'private'],
      filetype: arcs.completeFacet,
      filename: arcs.completeFacet,
      title: arcs.completeFacet,
      user: arcs.completeFacet,
      keyword: arcs.completeFacet,
      type: arcs.completeFacet,
      created: function() {
        return arcs.completeDate('resources/complete/created');
      },
      uploaded: function() {
        return arcs.completeDate('resources/complete/created');
      },
      modified: function() {
        return arcs.completeDate('resources/complete/modified');
      }
    };

    Search.prototype.initialize = function() {
      var _ref,
        _this = this;
      _ref = [this.options.query, this.options.page], this.query = _ref[0], this.page = _ref[1];
      this.collection = this.results = new arcs.collections.ResultSet;
      this.vs = VS.init({
        container: this.options.container,
        query: this.query,
        callbacks: {
          search: function(query, searchCollection) {
            _this.query = query;
            _this.options.page = 1;
            _this.options.onSearch(query);
            return _this.run();
          },
          facetMatches: function(callback) {
            return callback(_.keys(_this.facets));
          },
          valueMatches: function(facet, searchTerm, callback) {
            var val;
            val = _this.facets[facet];
            if (typeof val === 'function') {
              return callback(val(facet, encodeURIComponent(_this.query)));
            } else {
              return callback(val);
            }
          }
        }
      });
      if (this.options.run) return this.run();
    };

    Search.prototype.setQuery = function(query) {
      return this.vs.searchBox.setQuery(query);
    };

    Search.prototype.getLast = function() {
      return this.results.last(this.results.length % this.options.n || this.options.n);
    };

    Search.prototype.run = function(query, options) {
      var params,
        _this = this;
      options = _.extend(_.clone(this.options), options);
      if (query == null) query = this.vs.searchBox.value();
      params = ("?related&n=" + options.n) + ("&page=" + options.page) + ("&order=" + options.order) + ("&direction=" + options.direction);
      if (query) params += "&q=" + (encodeURIComponent(query));
      if (options.loader) arcs.loader.show();
      this.results.fetch({
        add: options.add,
        url: this.results.url() + params,
        success: function(set, res) {
          _this.results.query = res;
          options.success();
          if (options.loader) return arcs.loader.hide();
        },
        error: function() {
          options.error();
          if (options.loader) return arcs.loader.hide();
        }
      });
      this.query = this.vs.searchBox.value();
      this.page = options.page;
      return this.results;
    };

    return Search;

  })(Backbone.View);

}).call(this);
