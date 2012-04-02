(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Search = (function(_super) {

    __extends(Search, _super);

    function Search() {
      Search.__super__.constructor.apply(this, arguments);
    }

    Search.prototype.options = {
      sort: 'modified',
      grid: true,
      url: arcs.baseURL + 'search/',
      numResults: 30
    };

    /* Initialize and define events
    */

    Search.prototype.initialize = function() {
      this.setupSelect() && this.setupSearch();
      this.sort = 'modified';
      this.actions = new arcs.views.SearchActions({
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
      if (!this.router.searched) this.search.run();
      this.search.results.on('remove', this.render, this);
      arcs.on('arcs:selection', this.afterSelection, this);
      return arcs.keys.add('a', true, this.selectAll, this);
    };

    Search.prototype.events = {
      'click img': 'toggle',
      'click .result': 'maybeUnselectAll',
      'click #search-results': 'maybeUnselectAll',
      'click #grid-btn': 'toggleView',
      'click #list-btn': 'toggleView',
      'click #top-btn': 'scrollTop',
      'click .sort-btn': 'setSort'
    };

    /* More involved setups run by the initialize method
    */

    Search.prototype.setupSelect = function() {
      var _this = this;
      return this.$el.find('#search-results').selectable({
        distance: 20,
        filter: 'div.img-wrapper img',
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
      this.searchPage = 1;
      this.scrollReady = false;
      return this.search = new arcs.utils.Search({
        container: $('#search-wrapper'),
        order: this.options.sort,
        run: false,
        loader: true,
        success: function() {
          _this.router.navigate(encodeURIComponent(_this.search.query));
          _this.searchPage = 1;
          _this.render();
          if (!_this.scrollReady) {
            return _this.setupScroll() && (_this.scrollReady = true);
          }
        }
      });
    };

    Search.prototype.setupScroll = function() {
      var $actions, $results, $window, pos,
        _this = this;
      $actions = this.$('#search-actions');
      $results = this.$('#search-results');
      $window = $(window);
      pos = $actions.offset().top - 10;
      $window.scroll(function() {
        if ($window.scrollTop() > pos) {
          $actions.addClass('toolbar-fixed').width($results.width() + 23);
          _this.$('#top-btn').show();
        } else {
          $actions.removeClass('toolbar-fixed').width('auto');
          _this.$('#top-btn').hide();
        }
        if ($window.scrollTop() === $(document).height() - $window.height()) {
          if (_this.search.results.length % _this.options.numResults !== 0) return;
          _this.searchPage += 1;
          return _this.search.run(null, {
            add: true,
            page: _this.searchPage,
            order: _this.options.sort,
            success: function() {
              return _this.append();
            }
          });
        }
      });
      return $window.resize(function() {
        if ($window.scrollTop() > pos) {
          return $actions.width($results.width() + 23);
        }
      });
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
      var id;
      id = e.currentTarget.id;
      this.options.sort = e.target.id.match(/sort-(\w+)-btn/)[1];
      this.$('.sort-btn .icon-ok').remove();
      this.$(e.currentTarget).append(this.make('i', {
        "class": 'icon-ok'
      }));
      this.$('#sort-btn span#sort-by').html(this.options.sort);
      return this.search.run(null, {
        order: this.options.sort
      });
    };

    Search.prototype.unselectAll = function(trigger) {
      if (trigger == null) trigger = true;
      this.$('.result').removeClass('selected');
      if (trigger) return arcs.trigger('arcs:selection');
    };

    Search.prototype.selectAll = function(trigger) {
      if (trigger == null) trigger = true;
      this.$('.result').addClass('selected');
      if (trigger) return arcs.trigger('arcs:selection');
    };

    Search.prototype.toggle = function(e) {
      if (!(e.ctrlKey || e.shiftKey || e.metaKey)) this.unselectAll(false);
      $(e.currentTarget).parents('.result').toggleClass('selected');
      return arcs.trigger('arcs:selection');
    };

    Search.prototype.maybeUnselectAll = function(e) {
      if (!(e instanceof jQuery.Event)) return this.unselectAll();
      if (e.metaKey || e.ctrlKey || e.shiftKey) return false;
      if ($(e.target).attr('src')) return false;
      return this.unselectAll();
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
      var rest, results;
      if (!(this.search.results.length > this.options.numResults)) return;
      rest = this.search.results.rest(this.search.results.length - this.options.numResults);
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
      template = this.options.grid ? 'search/grid' : 'search/list';
      content = arcs.tmpl(template, results);
      if (append) {
        $results.append(content);
      } else {
        $results.html(content);
      }
      if (!this.search.results.length) {
        return $results.html(this.make('div', {
          id: 'no-results'
        }, 'No Results'));
      }
    };

    return Search;

  })(Backbone.View);

}).call(this);
