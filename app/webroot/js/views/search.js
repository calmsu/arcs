var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
}, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
arcs.views.Search = (function() {
  __extends(Search, Backbone.View);
  function Search() {
    Search.__super__.constructor.apply(this, arguments);
  }
  Search.prototype.initialize = function() {
    var query;
    $('.btn[rel=tooltip]').tooltip({
      placement: 'bottom'
    });
    this.setupSelect();
    query = arcs.utils.hash.get() || null;
    this.search = new arcs.utils.Search({
      container: $('#search-wrapper'),
      query: query,
      success: __bind(function() {
        return this.render();
      }, this)
    });
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
  Search.prototype.makeCollectionFromSelected = function(event, title, description) {
    var collection, el, ids;
    if (event == null) {
      event = null;
    }
    if (title == null) {
      title = null;
    }
    if (description == null) {
      description = null;
    }
    ids = (function() {
      var _i, _len, _ref, _results;
      _ref = this.getSelected().get();
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        el = _ref[_i];
        _results.push($(el).find('img').attr('data-id'));
      }
      return _results;
    }).call(this);
    title = title != null ? title : 'Temporary collection';
    description = description != null ? description : "Results from search, '" + (arcs.utils.hash.get()) + "'";
    arcs.log(title, description);
    collection = {
      Collection: {
        title: title,
        description: description,
        public: false,
        temporary: true
      },
      Members: ids
    };
    return $.ajax({
      url: arcs.baseURL + 'collections/create',
      data: JSON.stringify(collection),
      type: 'POST',
      contentType: 'application/json',
      success: __bind(function(data) {
        return window.open(arcs.baseURL + 'collection/' + data.id);
      }, this),
      error: __bind(function() {
        return this.notify("Not authorized", 'error');
      }, this)
    });
  };
  Search.prototype.getSelected = function() {
    return $('.result.selected');
  };
  Search.prototype.getAll = function() {
    return $('.result');
  };
  Search.prototype.unselectAll = function(e) {
    if (e == null) {
      e = null;
    }
    if ((e != null) && (e.metaKey || e.ctrlKey || e.shiftKey)) {
      return false;
    }
    if ((e != null) && $(e.target).attr('src')) {
      return false;
    }
    return this.getSelected().removeClass('selected');
  };
  Search.prototype.selectAll = function() {
    return this.getAll().addClass('selected');
  };
  Search.prototype.toggleAll = function() {
    return this.getAll().toggleClass('selected');
  };
  Search.prototype.selectResult = function(e) {
    if (!(e.ctrlKey || e.shiftKey || e.metaKey)) {
      this.unselectAll();
    }
    return $(e.currentTarget).parent('.result').toggleClass('selected');
  };
  Search.prototype.openResult = function(e) {
    var $el, id;
    arcs.log('called');
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
    var n, s;
    n = this.getSelected().length;
    s = n > 1 ? 's' : '';
    if (n === 0) {
      alert("You must select at least 1 result to tag.");
      return;
    }
    return arcs.utils.modal({
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
        }
      }
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
  Search.prototype.tagSelected = function(vals, modal, tagStr) {
    var n, that;
    if (tagStr == null) {
      tagStr = null;
    }
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
    if (noteStr == null) {
      noteStr = null;
    }
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
    if (type == null) {
      type = 'info';
    }
    $notify = $('#search-notify');
    $notify.html(msg).css('visibility', 'visible').removeClass("alert-info alert-error alert-success").addClass("alert-" + type);
    return window.setTimeout(function() {
      return $notify.css('visibility', 'hidden');
    }, 2000);
  };
  Search.prototype.gridView = function() {
    $('#list-btn').removeClass('active');
    $('#grid-btn').addClass('active');
    return this.render();
  };
  Search.prototype.listView = function() {
    var list;
    $('#grid-btn').removeClass('active');
    $('#list-btn').addClass('active');
    return this.render(list = true);
  };
  Search.prototype.render = function(list) {
    var template;
    if (list == null) {
      list = false;
    }
    if (list) {
      template = arcs.templates.resultsList;
    } else {
      template = arcs.templates.resultsGrid;
    }
    return $('#search-results').html(Mustache.render(template, {
      results: this.search.results.toJSON()
    }));
  };
  return Search;
})();