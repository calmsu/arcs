(function() {
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
      'click #collection-btn': 'collectionModal',
      'click #attribute-btn': 'attributeModal',
      'click #bookmark-btn': 'bookmarkSelected',
      'click #tag-btn': 'tagModal',
      'click #grid-btn': 'toggleView',
      'click #list-btn': 'toggleView'
    };

    /* Methods that return result DOM els or alter their states
    */

    Search.prototype._selected = function() {
      return this.$('.result.selected');
    };

    Search.prototype._all = function() {
      return this.$('.result');
    };

    Search.prototype._any = function() {
      return !!this._selected().length;
    };

    Search.prototype._nsel = function() {
      return this._selected().length;
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

    Search.prototype._anySelected = function() {
      if (!this._any()) {
        arcs.notify('Select at least one result', 'error');
        return false;
      }
      return true;
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
          $actions.width($results.width() + 23);
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
        if ($window.scrollTop() > 160) {
          return $actions.width($results.width() + 23);
        }
      });
    };

    /* Actions that take one or more search results
    */

    Search.prototype.openResult = function(e) {
      var $el;
      if (e instanceof jQuery.Event) {
        $el = $(e.currentTarget).parent();
        e.preventDefault();
      } else {
        $el = $(e);
      }
      return window.open(arcs.baseURL + 'resource/' + this._getModel($el).id);
    };

    Search.prototype.tagResult = function($result, tagStr) {
      var tag;
      tag = new arcs.models.Tag({
        resource_id: this._getModel($result).id,
        tag: tagStr
      });
      return tag.save({
        error: function() {
          return arcs.notify('Not authorized', 'error');
        }
      });
    };

    Search.prototype.bookmarkResult = function($result, noteStr) {
      var bkmk;
      if (noteStr == null) noteStr = null;
      bkmk = new arcs.models.Bookmark({
        resource_id: this._getModel($result).id,
        description: noteStr
      });
      return bkmk.save({
        error: function() {
          return arcs.notify('Not authorized', 'error');
        }
      });
    };

    Search.prototype.collectionFromSelected = function(vals) {
      var collection, ids, _ref, _ref2,
        _this = this;
      if (!this._anySelected()) return;
      collection = new arcs.models.Collection({
        title: (_ref = vals.title) != null ? _ref : "Temporary Collection",
        description: (_ref2 = vals.description) != null ? _ref2 : "Results from search '" + this.search.query + "'",
        public: false,
        temporary: true,
        members: ids
      });
      ids = _.map(this._selected().get(), function($el) {
        return _this._getModel($el).id;
      });
      return collection.save({
        members: ids
      }, {
        success: function(model) {
          return window.open(arcs.baseURL + 'collection/' + model.id);
        },
        error: function() {
          return arcs.notify('An error occurred.', 'error');
        }
      });
    };

    Search.prototype.tagModal = function() {
      var _ref;
      if (!this._anySelected()) return;
      return new arcs.views.Modal({
        title: 'Tag Selected',
        subtitle: ("" + (this._nsel()) + " resource" + ((0 < (_ref = this._nsel()) && _ref > 1) ? 's' : '')) + " will be tagged.",
        inputs: {
          tag: {
            label: false,
            multicomplete: arcs.utils.complete.tag,
            focused: true
          }
        },
        backdrop: true,
        buttons: {
          save: {
            "class": 'btn info',
            callback: this.tagSelected,
            context: this
          },
          cancel: function() {}
        }
      });
    };

    Search.prototype.attributeModal = function() {
      if (!this._anySelected()) return;
      return new arcs.views.Modal({
        title: 'Edit attributes',
        subtitle: ''
      });
    };

    Search.prototype.collectionModal = function() {
      var _ref;
      if (!this._anySelected()) return;
      return new arcs.views.Modal({
        title: 'Create a Collection',
        subtitle: ("A collection with " + (this._nsel()) + " ") + ("resource" + ((0 < (_ref = this._nsel()) && _ref > 1) ? 's' : '') + " will be created."),
        inputs: {
          title: {
            focused: true
          },
          description: {
            type: 'textarea'
          }
        },
        buttons: {
          save: {
            "class": 'btn success',
            callback: this.collectionFromSelected,
            context: this
          },
          cancel: function() {}
        }
      });
    };

    Search.prototype.bookmarkSelected = function() {
      return this._doForSelected(this.bookmarkResult, ['bookmark', 'bookmarked']);
    };

    Search.prototype.openSelected = function() {
      return this._doForSelected(this.openResult, ['open', 'opened']);
    };

    Search.prototype.tagSelected = function(val) {
      var tag;
      tag = _.isString(val) ? val : val.tag;
      return this._doForSelected(this.tagResult, tag, ['tag', 'tagged']);
    };

    Search.prototype._getModel = function($result) {
      var id;
      id = $($result).find('img').attr('data-id');
      return this.search.results.get(id);
    };

    Search.prototype._doForSelected = function() {
      var cbk, cbkArgs, n, name, _i, _ref, _ref2,
        _this = this;
      cbk = arguments[0], cbkArgs = 3 <= arguments.length ? __slice.call(arguments, 1, _i = arguments.length - 1) : (_i = 1, []), name = arguments[_i++];
      if (!this._anySelected()) return;
      this._selected().each(function(i, el) {
        return cbk.call.apply(cbk, [_this, el].concat(__slice.call(cbkArgs)));
      });
      n = this._selected().length;
      return arcs.notify(("" + (this._nsel()) + " resource" + ((0 < (_ref = this._nsel()) && _ref > 1) ? 's' : void 0) + " ") + ("" + ((0 < (_ref2 = this._nsel()) && _ref2 > 1) ? "were" : "was") + " " + name[1]), 'success');
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
      template = this.grid ? 'search/grid' : 'search/list';
      content = arcs.tmpl(template, results, (!this.grid ? _.template : void 0));
      if (append) {
        $results.append(content);
      } else {
        $results.html(content);
      }
      if (!this._all().length) {
        return $results.html(this.make('div', {
          id: 'no-results'
        }, 'No Results'));
      }
    };

    return Search;

  })(Backbone.View);

}).call(this);
