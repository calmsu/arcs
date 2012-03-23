(function() {

  arcs.utils.Search = (function() {

    function Search(options) {
      var defaults,
        _this = this;
      defaults = {
        container: null,
        query: '',
        loader: false,
        order: 'modified',
        add: false,
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
            _this.query = query;
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

    Search.prototype.setQuery = function(query) {
      return this.vs.searchBox.setQuery(query);
    };

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
      keyword: function() {
        return arcs.utils.complete.keyword();
      },
      collection: function() {
        return arcs.utils.complete.collection();
      },
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

    Search.prototype.run = function(facets, options) {
      var defaults, offset, params,
        _this = this;
      defaults = {
        n: 30,
        page: 1
      };
      options = _.extend(defaults, this.options, options);
      if (!(facets != null) && (this.vs != null)) {
        facets = this.vs.searchQuery.toJSON();
      }
      _.each(facets, function(f) {
        return delete f.app;
      });
      offset = (options.page - 1) * options.n;
      params = "?n=" + options.n + "&offset=" + offset + "&order=" + options.order;
      if (this.options.loader) arcs.utils.loader.show();
      this.results.fetch({
        add: options.add,
        data: JSON.stringify(facets),
        type: 'POST',
        url: this.results.url() + params,
        contentType: 'application/json',
        success: function() {
          options.success();
          if (_this.options.loader) return arcs.utils.loader.hide();
        },
        error: function() {
          options.error();
          if (_this.options.loader) return arcs.utils.loader.hide();
        }
      });
      this.query = this.vs.searchBox.value();
      return this.results;
    };

    return Search;

  })();

}).call(this);
