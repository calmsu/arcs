(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  if ((_base = arcs.views).search == null) _base.search = {};

  arcs.views.search.Search = (function(_super) {

    __extends(Search, _super);

    function Search() {
      Search.__super__.constructor.apply(this, arguments);
    }

    Search.prototype.options = {
      sort: 'title',
      sortDir: 'asc',
      grid: true,
      url: arcs.baseURL + 'search/'
    };

    /* Initialize and define events
    */

    Search.prototype.initialize = function() {
      this.setupSelect();
      this.setupSearch();
      this.actions = new arcs.views.search.Actions({
        el: this.$el,
        collection: this.search.results
      });
      this.router = new arcs.routers.Search({
        search: this.search
      });
      Backbone.history.start({
        pushState: true,
        root: this.options.url
      });
      if (!this.router.searched) {
        this.search.run(null, {
          order: this.options.sort,
          direction: this.options.sortDir
        });
      }
      this.search.results.on('change remove', this.render, this);
      arcs.bus.on('selection', this.afterSelection, this);
      arcs.keys.map(this, {
        'ctrl+a': this.selectAll,
        '?': this.showHotkeys,
        t: this.scrollTop
      });
      return this.setupHelp();
    };

    Search.prototype.events = {
      'click img': 'toggle',
      'click .result': 'maybeUnselectAll',
      'click #search-results': 'maybeUnselectAll',
      'click #grid-btn': 'toggleView',
      'click #list-btn': 'toggleView',
      'click #top-btn': 'scrollTop',
      'click .sort-btn': 'setSort',
      'click .dir-btn': 'setSortDir',
      'click .search-page-btn': 'setPage'
    };

    /* More involved setups run by the initialize method
    */

    Search.prototype.setupSelect = function() {
      var _this = this;
      return this.$el.find('#search-results').selectable({
        distance: 20,
        filter: '.img-wrapper img',
        selecting: function(e, ui) {
          $(ui.selecting).parents('.result').addClass('selected');
          return _this.afterSelection();
        },
        selected: function(e, ui) {
          $(ui.selected).parents('.result').addClass('selected');
          return _this.afterSelection();
        },
        unselecting: function(e, ui) {
          $(ui.unselecting).parents('.result').removeClass('selected');
          return _this.afterSelection();
        },
        unselected: function(e, ui) {
          $(ui.unselected).parents('.result').removeClass('selected');
          return _this.afterSelection();
        }
      });
    };

    Search.prototype.setupSearch = function() {
      var _this = this;
      this.scrollReady = false;
      return this.search = new arcs.utils.Search({
        container: $('.search-wrapper'),
        order: this.options.sort,
        run: false,
        loader: true,
        success: function() {
          _this.router.navigate("" + (encodeURIComponent(_this.search.query)) + "/p" + _this.search.page);
          if (!_this.scrollReady) {
            _this.setupScroll() && (_this.scrollReady = true);
          }
          _this.setupHelp();
          return _this.render();
        }
      });
    };

    Search.prototype.setupScroll = function() {
      var $actions, $results, $window, pos, _ref,
        _this = this;
      _ref = [this.$('#search-actions'), this.$('#search-results')], $actions = _ref[0], $results = _ref[1];
      $window = $(window);
      pos = $actions.offset().top - 10;
      $window.scroll(function() {
        if ($window.scrollTop() > pos) {
          return $actions.addClass('toolbar-fixed').width($results.width() + 22);
        } else {
          return $actions.removeClass('toolbar-fixed').width('auto');
        }
      });
      return $window.resize(function() {
        if ($window.scrollTop() > pos) {
          return $actions.width($results.width() + 23);
        }
      });
    };

    Search.prototype.setupHelp = function() {
      if (!$('.search-help-btn').length) {
        $('.VS-search-inner').append(arcs.tmpl('search/help-toggle'));
        $('.search-help-btn').click(this.showHelp);
        return $('.search-help-close').click(this.closeHelp);
      }
    };

    Search.prototype.toggleView = function() {
      this.options.grid = !this.options.grid;
      this.$('#grid-btn').toggleClass('active');
      this.$('#list-btn').toggleClass('active');
      return this.render();
    };

    Search.prototype.scrollTop = function() {
      var time;
      time = ($(window).scrollTop() / $(document).height()) * 1000;
      return $('html, body').animate({
        scrollTop: 0
      }, time);
    };

    Search.prototype.setSort = function(e) {
      this.options.sort = e.target.id.match(/sort-(\w+)-btn/)[1];
      this.$('.sort-btn .icon-ok').remove();
      this.$(e.target).append(this.make('i', {
        "class": 'icon-ok'
      }));
      this.$('#sort-btn span#sort-by').html(this.options.sort);
      return this.search.run(null, {
        order: this.options.sort,
        direction: this.options.sortDir
      });
    };

    Search.prototype.setSortDir = function(e) {
      this.options.sortDir = e.target.id.match(/dir-(\w+)-btn/)[1];
      this.$('.dir-btn .icon-ok').remove();
      this.$(e.target).append(this.make('i', {
        "class": 'icon-ok'
      }));
      return this.search.run(null, {
        order: this.options.sort,
        direction: this.options.sortDir
      });
    };

    Search.prototype.setPage = function(e) {
      var $el;
      e.preventDefault();
      $el = $(e.currentTarget);
      this.search.options.page = $el.data('page');
      return this.search.run();
    };

    Search.prototype.unselectAll = function(trigger) {
      if (trigger == null) trigger = true;
      this.$('.result').removeClass('selected');
      if (trigger) return arcs.bus.trigger('selection');
    };

    Search.prototype.selectAll = function(trigger) {
      if (trigger == null) trigger = true;
      this.$('.result').addClass('selected');
      if (trigger) return arcs.bus.trigger('selection');
    };

    Search.prototype.toggle = function(e) {
      if (!(e.ctrlKey || e.shiftKey || e.metaKey)) this.unselectAll(false);
      $(e.currentTarget).parents('.result').toggleClass('selected');
      return arcs.bus.trigger('selection');
    };

    Search.prototype.maybeUnselectAll = function(e) {
      if (!(e instanceof jQuery.Event)) return this.unselectAll();
      if (e.metaKey || e.ctrlKey || e.shiftKey) return false;
      if ($(e.target).attr('src')) return false;
      return this.unselectAll();
    };

    Search.prototype.showHotkeys = function() {
      if ($('.hotkeys-modal').length) return $('.hotkeys-modal').remove();
      return new arcs.views.Hotkeys({
        template: 'search/hotkeys'
      });
    };

    Search.prototype.showHelp = function() {
      return $('.search-help').show();
    };

    Search.prototype.closeHelp = function() {
      return $('.search-help').hide();
    };

    /* Render the search results
    */

    Search.prototype.afterSelection = function() {
      var _this = this;
      return _.defer(function() {
        var selected;
        selected = $('.result.selected').map(function() {
          return $(this).data('id');
        }).get();
        _this.search.results.unselectAll();
        if (selected.length) _this.search.results.select(selected);
        if (_this.search.results.anySelected()) {
          $('.btn.needs-resource').removeClass('disabled');
          return $('#search input').blur();
        } else {
          return $('.btn.needs-resource').addClass('disabled');
        }
      });
    };

    Search.prototype.append = function() {
      var results;
      if (!(this.search.results.length > this.search.options.n)) return;
      results = new arcs.collections.ResultSet(this.search.getLast());
      return this._render({
        results: results.toJSON()
      }, true);
    };

    Search.prototype.render = function() {
      var data;
      this._render({
        results: this.search.results.toJSON()
      });
      data = this.search.results.query;
      data.page = this.search.page;
      data.query = encodeURIComponent(this.search.query);
      return $('#search-pagination').html(arcs.tmpl('search/paginate', {
        results: data
      }));
    };

    Search.prototype._render = function(results, append) {
      var $results, template;
      if (append == null) append = false;
      $results = $('#search-results');
      template = this.options.grid ? 'search/grid' : 'search/list';
      $results[append ? 'append' : 'html'](arcs.tmpl(template, results));
      if (!this.search.results.length) {
        return $results.html(this.make('div', {
          id: 'no-results'
        }, 'No Results'));
      }
    };

    return Search;

  })(Backbone.View);

}).call(this);
