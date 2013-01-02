(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.CollectionList = (function(_super) {

    __extends(CollectionList, _super);

    function CollectionList() {
      CollectionList.__super__.constructor.apply(this, arguments);
    }

    CollectionList.prototype.initialize = function() {
      var _this = this;
      this.search = new arcs.utils.Search({
        container: $('.search-wrapper'),
        run: false,
        onSearch: function() {
          return location.href = arcs.url('search', _this.search.query);
        }
      });
      this.render();
      return this.$('details.open').each(function(i, el) {
        return _this.renderDetails($(el));
      });
    };

    CollectionList.prototype.events = {
      'click summary': 'onClick',
      'click details.closed': 'onClick',
      'click #delete-btn': 'deleteCollection'
    };

    CollectionList.prototype.onClick = function(e) {
      var $el, _ref;
      if (e.currentTarget.tagName === 'DETAILS') {
        $el = $(e.currentTarget);
      } else {
        $el = $(e.currentTarget).parent();
      }
      console.log($el);
      $el.toggleAttr('open');
      $el.toggleClass('closed').toggleClass('open');
      this.renderDetails($el);
      if (((_ref = e.srcElement.tagName) !== 'SPAN' && _ref !== 'BUTTON' && _ref !== 'I' && _ref !== 'A')) {
        e.preventDefault();
        return false;
      }
    };

    CollectionList.prototype.deleteCollection = function(e) {
      var $parent, id, model,
        _this = this;
      e.preventDefault();
      $parent = $(e.currentTarget).parents('details');
      id = $parent.data('id');
      model = this.collection.get(id);
      return arcs.confirm("Are you sure you want to delete this collection?", ("<p>Collection <b>" + (model.get('title')) + "</b> will be ") + "deleted. <p><b>N.B.</b> Resources within the collection will not be " + "deleted. They may still be accessed from other collections to which they " + "belong.", function() {
        arcs.loader.show();
        return $.ajax({
          url: arcs.url('collections', 'delete', model.id),
          type: 'DELETE',
          success: function() {
            _this.collection.remove(model, {
              silent: true
            });
            _this.render();
            return arcs.loader.hide();
          }
        });
      });
    };

    CollectionList.prototype.render = function() {
      this.$el.html(arcs.tmpl('collections/list', {
        collections: this.collection.toJSON()
      }));
      return this;
    };

    CollectionList.prototype.renderDetails = function($el) {
      var id, query;
      id = $el.data('id');
      query = encodeURIComponent('collection_id:"' + id + '"');
      return $.getJSON(arcs.baseURL + ("resources/search?n=12&q=" + query), function(response) {
        return $el.children('.results').html(arcs.tmpl('home/details', {
          resources: response.results,
          searchURL: arcs.baseURL + ("collection/" + id)
        }));
      });
    };

    return CollectionList;

  })(Backbone.View);

}).call(this);
