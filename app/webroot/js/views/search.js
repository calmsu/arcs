var __hasProp = Object.prototype.hasOwnProperty,
  __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

arcs.views.Search = (function(_super) {

  __extends(Search, _super);

  function Search() {
    Search.__super__.constructor.apply(this, arguments);
  }

  Search.prototype.initialize = function() {
    var _this = this;
    this.setupSelect();
    this.search = new arcs.utils.Search({
      container: $('#search-wrapper'),
      run: false,
      loader: true,
      success: function() {
        _this.router.navigate(_this.search.query);
        return _this.render();
      }
    });
    this.router = new arcs.routers.Search({
      search: this.search
    });
    Backbone.history.start({
      pushState: true,
      root: arcs.baseURL + 'search/'
    });
    if (!this.router.searched) this.search.run();
    this.searchPage = 1;
    $(window).scroll(function() {
      if ($(window).scrollTop() === $(document).height() - $(window).height()) {
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
    this.view = 'grid';
    return arcs.utils.keys.add('a', true, this.selectAll, this);
  };

  Search.prototype.events = {
    'dblclick img': 'openResult',
    'click img': 'selectResult',
    'click .result': 'unselectAll',
    'click #search-results': 'unselectAll',
    'click #open-btn': 'openSelected',
    'click #open-colview-btn': 'makeCollectionFromSelected',
    'click #collection-btn': 'makeCollectionFromSelected',
    'click #bookmark-btn': 'bookmarkSelected',
    'click #tag-btn': 'tagModal',
    'click #grid-btn': 'gridView',
    'click #list-btn': 'listView'
  };

  Search.prototype.setupSelect = function() {
    return $('#search-results').selectable({
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

  Search.prototype.results = {
    selected: function() {
      return $('.result.selected');
    },
    all: function() {
      return $('.result');
    },
    select: function(e) {
      if (!(e.ctrlKey || e.shiftKey || e.metaKey)) this.unselectAll();
      return $(e.currentTarget).parent('.result').toggleClass('selected');
    },
    toggle: function(e) {},
    selectAll: function() {
      return this.results.all().addClass('selected');
    },
    toggleAll: function() {
      return this.results.all().toggleClass('selected');
    },
    unselectAll: function(e) {
      return this.results.all().removeClass('selected');
    },
    maybeUnselectAll: function(e) {
      if (e != null) {
        if (e.metaKey || e.ctrlKey || e.shiftKey) return false;
        if ($(e.target).attr('src')) return false;
      }
      return this.results.unselectAll();
    },
    open: function(e) {
      var $el, id;
      if (e instanceof jQuery.Event) {
        $el = $(e.currentTarget).parent();
        e.preventDefault();
      } else {
        $el = $(e);
      }
      id = $el.find('img').attr('data-id');
      return window.open(arcs.baseURL + 'resource/' + id);
    },
    openSelected: function() {
      var that;
      that = this;
      return this.results.selected().each(function() {
        return that.openResult(this);
      });
    }
  };

  Search.prototype.makeCollectionFromSelected = function(event) {
    var collection, ids, uri,
      _this = this;
    ids = _.map(this.getSelected().get(), function(el) {
      return $(el).find('img').attr('data-id');
    });
    if (typeof description === "undefined" || description === null) {
      description = "Results from search, '" + (arcs.utils.hash.get(uri = true)) + "'";
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
        return _this.notify();
      }
    });
  };

  Search.prototype.getSelected = function() {
    return $('.result.selected');
  };

  Search.prototype.getAll = function() {
    return $('.result');
  };

  Search.prototype.unselectAll = function(e) {
    if ((e != null) && (e.metaKey || e.ctrlKey || e.shiftKey)) return false;
    if ((e != null) && $(e.target).attr('src')) return false;
    return this.getSelected().removeClass('selected');
  };

  Search.prototype.selectAll = function() {
    return this.getAll().addClass('selected');
  };

  Search.prototype.toggleAll = function() {
    return this.getAll().toggleClass('selected');
  };

  Search.prototype.selectResult = function(e) {
    if (!(e.ctrlKey || e.shiftKey || e.metaKey)) this.unselectAll();
    return $(e.currentTarget).parent('.result').toggleClass('selected');
  };

  Search.prototype.openResult = function(e) {
    var $el, id;
    if (e instanceof jQuery.Event) {
      $el = $(e.currentTarget).parent();
      e.preventDefault();
    } else {
      $el = $(e);
    }
    id = $el.find('img').attr('data-id');
    return window.open(arcs.baseURL + 'resource/' + id);
  };

  Search.prototype.tagModal = function() {
    var modal, n, s;
    n = this.getSelected().length;
    s = n > 1 ? 's' : '';
    if (!n) {
      alert("You must select at least 1 result to tag.");
      return;
    }
    modal = new arcs.utils.Modal({
      template: arcs.templates.searchModal,
      templateValues: {
        title: 'Tag Selected',
        message: "" + n + " resource" + s + " will be tagged."
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
    modal.el.find('#search-modal-value').focus();
    return arcs.utils.autocomplete({
      sel: '#search-modal-value',
      source: arcs.utils.complete.tag()
    });
  };

  Search.prototype.tagResult = function(el, tagStr) {
    var id, tag;
    id = $(el).find('img').attr('data-id');
    tag = new arcs.models.Tag({
      resource_id: id,
      tag: tagStr
    });
    return tag.save({
      error: function() {
        return this.notify('Not authorized', 'error');
      }
    });
  };

  Search.prototype.tagSelected = function(vals, tagStr) {
    var n, that;
    tagStr = tagStr != null ? tagStr : vals['search-modal-value'];
    n = this.getSelected().length;
    that = this;
    this.getSelected().each(function() {
      return that.tagResult(this, tagStr);
    });
    return this.notify("" + n + " resources were tagged with " + tagStr);
  };

  Search.prototype.bookmarkResult = function(el, noteStr) {
    var bkmk, id;
    if (noteStr == null) noteStr = null;
    id = $(el).find('img').attr('data-id');
    bkmk = new arcs.models.Bookmark({
      resource_id: id,
      description: noteStr
    });
    return bkmk.save({
      error: function() {
        return this.notify('Not authorized', 'error');
      }
    });
  };

  Search.prototype.bookmarkSelected = function() {
    var n, that;
    n = this.getSelected().length;
    that = this;
    this.getSelected().each(function() {
      return that.bookmarkResult(this);
    });
    return this.notify("" + n + " resources were bookmarked");
  };

  Search.prototype.openSelected = function() {
    var that;
    that = this;
    return this.getSelected().each(function() {
      return that.openResult(this);
    });
  };

  Search.prototype.notify = function(msg, type) {
    var $notify;
    if (type == null) type = 'info';
    $notify = $('#search-notify');
    $notify.html(msg).css('visibility', 'visible').removeClass("alert-info alert-error alert-success").addClass("alert-" + type);
    return window.setTimeout(function() {
      return $notify.css('visibility', 'hidden');
    }, 2000);
  };

  Search.prototype.gridView = function() {
    $('#list-btn').removeClass('active');
    $('#grid-btn').addClass('active');
    this.view = 'grid';
    return this.render();
  };

  Search.prototype.listView = function() {
    $('#grid-btn').removeClass('active');
    $('#list-btn').addClass('active');
    this.view = 'list';
    return this.render();
  };

  Search.prototype.append = function() {
    var rest, results;
    rest = this.search.results.rest(this.results.all().length);
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
    if (this.view === 'list') {
      template = arcs.templates.resultsList;
    } else {
      template = arcs.templates.resultsGrid;
    }
    content = Mustache.render(template, results);
    if (append) {
      $results.append(content);
    } else {
      $results.html(content);
    }
    if (!this.results.all().length) {
      return $results.html('<div id="no-results">No Results</div>');
    }
  };

  return Search;

})(Backbone.View);
