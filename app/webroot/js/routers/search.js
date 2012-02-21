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
    ':query': 'doSearch'
  };

  Search.prototype.doSearch = function(query) {
    this.search.setQuery(query);
    this.search.run();
    this.navigate(this.search.query);
    return this.searched = true;
  };

  return Search;

})(Backbone.Router);
