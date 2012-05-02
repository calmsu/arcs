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
      direction: 'desc',
      page: 1,
      n: 30,
      add: false,
      run: true,
      onSearch: function() {},
      success: function() {},
      error: function() {}
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
            _this.options.onSearch(query);
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
      if (this.options.run) return this.run();
    };

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
        return arcs.complete('resources/complete/title');
      },
      user: function() {
        return arcs.complete('users/complete');
      },
      keyword: function() {
        return arcs.complete('keywords/complete');
      },
      collection: function() {
        return arcs.complete('collections/complete');
      },
      created: function() {
        return arcs.completeDate('resources/complete/created');
      },
      uploaded: function() {
        return arcs.completeDate('resources/complete/created');
      },
      modified: function() {
        return arcs.completeDate('resources/complete/modified');
      },
      type: function() {
        return _.compact(_.keys(arcs.config.types));
      }
    };

    Search.prototype.getLast = function() {
      return this.results.last(this.results.length % this.options.n || this.options.n);
    };

    Search.prototype.run = function(query, options) {
      var params, q, _i, _len,
        _this = this;
      options = _.extend(_.clone(this.options), options);
      params = ("?related&n=" + options.n + "&page=" + options.page) + ("&order=" + options.order + "&direction=" + options.direction);
      if (query == null) query = this.vs.searchQuery.toJSON();
      for (_i = 0, _len = query.length; _i < _len; _i++) {
        q = query[_i];
        delete q.app;
      }
      if (options.loader) arcs.loader.show();
      this.results.fetch({
        add: options.add,
        data: JSON.stringify(query),
        type: 'POST',
        url: this.results.url() + params,
        contentType: 'application/json',
        success: function() {
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
