(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.routers.Search = (function(_super) {

    __extends(Search, _super);

    function Search() {
      Search.__super__.constructor.apply(this, arguments);
    }

    Search.prototype.initialize = function(options) {
      return this.search = options.search;
    };

    Search.prototype.routes = {
      '': 'root',
      'p:page': 'emptyWithPage',
      ':query/p:page': 'doSearch',
      ':query': 'doSearch'
    };

    Search.prototype.root = function() {
      return this.hasTrailing = true && this.doSearch();
    };

    Search.prototype.navigate = function(fragment, options) {
      arcs.log('navigate', fragment);
      options || (options = {});
      if (!this.hasTrailing) {
        options.replace = true;
        this.hasTrailing = true;
      }
      return Search.__super__.navigate.call(this, fragment, options);
    };

    Search.prototype.emptyWithPage = function(page) {
      if (/\d+/.test(page)) {
        return this.doSearch('', page);
      } else {
        return this.doSearch(page);
      }
    };

    Search.prototype.doSearch = function(query, page) {
      if (query == null) query = '';
      if (page == null) page = 1;
      if (query === 'search') {
        return this.navigate('//p1', {
          replace: true
        });
      }
      this.search.setQuery(query);
      this.search.options.page = parseInt(page);
      this.search.run();
      this.navigate("" + (encodeURIComponent(this.search.query)) + "/p" + page);
      return this.searched = true;
    };

    return Search;

  })(Backbone.Router);

}).call(this);
