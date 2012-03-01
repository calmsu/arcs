var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; },
  __slice = Array.prototype.slice;

arcs.views.Search = (function(_super) {

  __extends(Search, _super);

  function Search() {
    Search.__super__.constructor.apply(this, arguments);
  }

  /* Initialize and define events
  */

  Search.prototype.initialize = function() {
    this.setupSelect();
    this.setupSearch();
    this.router = new arcs.routers.Search({
      search: this.search
    });
    Backbone.history.start({
      pushState: true,
      root: arcs.baseURL + 'search/'
    });
    if (!this.router.searched) this.search.run();
    if (this.grid == null) this.grid = true;
    arcs.utils.keys.add('a', true, this._selectAll, this);
    return arcs.utils.keys.add('o', true, this.openSelected, this);
  };

  Search.prototype.events = {
    'click img': '_select',
    'click .result': '_maybeUnselectAll',
    'click #search-results': '_maybeUnselectAll',
    'dblclick img': 'openResult',
    'click #open-btn': 'openSelected',
    'click #open-colview-btn': 'collectionFromSelected',
    'click #collection-btn': 'collectionFromSelected',
    'click #bookmark-btn': 'bookmarkSelected',
    'click #tag-btn': 'tagModal',
    'click #grid-btn': 'toggleView',
    'click #list-btn': 'toggleView'
  };

  /* Methods that return result DOM els or alter their states
  */

  Search.prototype._selected = function() {
    return $('.result.selected');
  };

  Search.prototype._all = function() {
    return $('.result');
  };

  Search.prototype._any = function() {
    return !!$('.result.selected').length;
  };

  Search.prototype._selectAll = function() {
    return this._all().addClass('selected');
  };

  Search.prototype._toggleAll = function() {
    return this._all().toggleClass('selected');
  };

  Search.prototype._unselectAll = function() {
    return this._all().removeClass('selected');
  };

  Search.prototype._select = function(e) {
    if (!(e.ctrlKey || e.shiftKey || e.metaKey)) this._unselectAll();
    return $(e.currentTarget).parent('.result').toggleClass('selected');
  };

  Search.prototype._maybeUnselectAll = function(e) {
    if (!(e instanceof jQuery.Event)) return this._unselectAll();
    if (e.metaKey || e.ctrlKey || e.shiftKey) return false;
    if ($(e.target).attr('src')) return false;
    return this._unselectAll();
  };

  /* More involved setups run by the initialize method
  */

  Search.prototype.setupSelect = function() {
    return this.$el.find('#search-results').selectable({
      distance: 20,
      filter: 'img',
      selecting: function(e, ui) {
        return $(ui.selecting).parent().addClass('selected');
      },
      selected: function(e, ui) {
        return $(ui.selected).parent().addClass('selected');
      },
      unselecting: function(e, ui) {
        return $(ui.unselecting).parent().removeClass('selected');
      },
      unselected: function(e, ui) {
        return $(ui.unselected).parent().removeClass('selected');
      }
    });
  };

  Search.prototype.setupSearch = function() {
    var $actions, $results, $window,
      _this = this;
    this.search = new arcs.utils.Search({
      container: $('#search-wrapper'),
      run: false,
      loader: true,
      success: function() {
        _this.router.navigate(_this.search.query);
        return _this.render();
      }
    });
    this.searchPage = 1;
    $actions = $('#search-actions');
    $window = $(window);
    $results = $('#search-results');
    $window.scroll(function() {
      if ($window.scrollTop() > 160) {
        $actions.addClass('toolbar-fixed');
        $actions.width($results.width() + 22);
      } else {
        $actions.removeClass('toolbar-fixed');
        $actions.width('auto');
      }
      if ($window.scrollTop() === $(document).height() - $window.height()) {
        _this.searchPage += 1;
        return _this.search.run(null, {
          add: true,
          page: _this.searchPage,
          success: function() {
            return _this.append();
          }
        });
      }
    });
    return $window.resize(function() {
      if ($window.scrollTop() > 160) return $actions.width($results.width() + 22);
    });
  };

  /* Actions that take one or more search results
  */

  Search.prototype.openResult = function(e) {
    var $el, resource;
    if (e instanceof jQuery.Event) {
      $el = $(e.currentTarget).parent();
      e.preventDefault();
    } else {
      $el = $(e);
    }
    resource = this._getModel($el);
    arcs.log(resource);
    return window.open(arcs.baseURL + 'resource/' + resource.id);
  };

  Search.prototype.tagResult = function($result, tagStr) {
    var resource, tag;
    resource = this._getModel($result);
    tag = new arcs.models.Tag({
      resource_id: resource.id,
      tag: tagStr
    });
    return tag.save({
      error: function() {
        return arcs.utils.notify('Not authorized', 'error');
      }
    });
  };

  Search.prototype.bookmarkResult = function($result, noteStr) {
    var bkmk, resource;
    if (noteStr == null) noteStr = null;
    resource = this._getModel($result);
    bkmk = new arcs.models.Bookmark({
      resource_id: resource.id,
      description: noteStr
    });
    return bkmk.save({
      error: function() {
        return arcs.utils.notify('Not authorized', 'error');
      }
    });
  };

  Search.prototype.collectionFromSelected = function(event) {
    var collection, ids,
      _this = this;
    ids = _.map(this._selected().get(), function($el) {
      return _this._getModel($el).id;
    });
    if (typeof description === "undefined" || description === null) {
      description = "Results from search '" + this.search.query + "'";
    }
    collection = new arcs.models.Collection({
      public: false,
      temporary: true,
      members: ids
    });
    return collection.save({
      description: description
    }, {
      success: function(model) {
        return window.open(arcs.baseURL + 'collection/' + model.id);
      },
      error: function() {
        return arcs.utils.notify('An error occurred.', 'error');
      }
    });
  };

  Search.prototype.tagModal = function() {
    var $modal;
    if (!this._any()) {
      return arcs.utils.notify("You must select a resource to tag", 'error');
    }
    $modal = new arcs.utils.Modal({
      template: 'searchModal',
      templateValues: {
        title: 'Tag Selected',
        message: ("" + (this._selected().length) + " resource" + ((0 < n && n > 1) ? 's' : void 0)) + " will be tagged."
      },
      inputs: ['search-modal-value'],
      backdrop: true,
      buttons: {
        save: {
          callback: this.tagSelected,
          context: this
        },
        cancel: function() {}
      }
    });
    $modal.el.find('#search-modal-value').focus();
    return arcs.utils.autocomplete({
      sel: '#search-modal-value',
      source: arcs.utils.complete.tag()
    });
  };

  Search.prototype.bookmarkSelected = function() {
    return this._doForSelected(this.bookmarkResult, ['bookmark', 'bookmarked']);
  };

  Search.prototype.openSelected = function() {
    return this._doForSelected(this.openResult, ['open', 'opened']);
  };

  Search.prototype.tagSelected = function(vals, tagStr) {
    tagStr = tagStr != null ? tagStr : vals['search-modal-value'];
    return this._doForSelected(this.tagResult, tagStr, ['tag', 'tagged']);
  };

  Search.prototype._getModel = function($result) {
    var id;
    id = $($result).find('img').attr('data-id');
    return this.search.results.get(id);
  };

  Search.prototype._doForSelected = function() {
    var cbk, cbkArgs, n, name, _i,
      _this = this;
    cbk = arguments[0], cbkArgs = 3 <= arguments.length ? __slice.call(arguments, 1, _i = arguments.length - 1) : (_i = 1, []), name = arguments[_i++];
    if (!this._any()) {
      return arcs.utils.notify('You must select a resource to ' + name[0], 'error');
    }
    this._selected().each(function(i, el) {
      return cbk.call.apply(cbk, [_this, el].concat(__slice.call(cbkArgs)));
    });
    n = this._selected().length;
    return arcs.utils.notify(("" + n + " resource" + ((0 < n && n > 1) ? 's' : void 0) + " ") + ("" + ((0 < n && n > 1) ? "were" : "was") + " " + name[1]), 'success');
  };

  Search.prototype.toggleView = function() {
    this.grid = !this.grid;
    $('#grid-btn').toggleClass('active');
    $('#list-btn').toggleClass('active');
    return this.render();
  };

  /* Render the search results
  */

  Search.prototype.append = function() {
    var rest, results;
    rest = this.search.results.rest(this._all().length);
    results = new arcs.collections.ResultSet(rest);
    return this._render({
      results: results.toJSON()
    }, true);
  };

  Search.prototype.render = function() {
    return this._render({
      results: this.search.results.toJSON()
    });
  };

  Search.prototype._render = function(results, append) {
    var $results, content, template;
    if (append == null) append = false;
    $results = $('#search-results');
    template = this.grid ? 'resultsGrid' : 'resultsList';
    content = arcs.tmpl(template, results);
    if (append) {
      $results.append(content);
    } else {
      $results.html(content);
    }
    if (!this._all().length) {
      return $results.html('<div id="no-results">No Results</div>');
    }
  };

  return Search;

})(Backbone.View);
