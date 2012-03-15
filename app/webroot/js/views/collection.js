(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Collection = (function(_super) {

    __extends(Collection, _super);

    function Collection() {
      Collection.__super__.constructor.apply(this, arguments);
    }

    Collection.prototype.initialize = function() {
      var _ref, _ref2,
        _this = this;
      arcs.on('arcs:indexchange', this.set, this);
      arcs.on('arcs:indexchange', function() {
        return arcs.log('arcs:indexchange', arguments);
      });
      arcs.keys.add('left', false, this.prev, this);
      arcs.keys.add('right', false, this.next, this);
      this.discussion = new arcs.views.Discussion({
        el: $('#discussion')
      });
      this.keywords = new arcs.views.Keyword({
        el: $('#information')
      });
      this.hotspots = new arcs.views.Hotspot({
        el: $('#resource')
      });
      this.toolbar = new arcs.views.Toolbar({
        el: $('#toolbar')
      });
      this.carousel = new arcs.views.Carousel({
        el: $('#carousel-wrapper'),
        collection: this.collection,
        index: (_ref = this.index) != null ? _ref : 0
      });
      this.router = new arcs.routers.Resource;
      Backbone.history.start({
        pushState: true,
        root: arcs.baseURL + (this.collection.length ? 'collection/' : 'resource/')
      });
      $(window).resize(function() {
        return arcs.trigger('arcs:resourceresize');
      });
      if (this.model.get('first_req')) {
        if (this.model.get('mime_type' === 'application/pdf')) this.splitPrompt();
      }
      return (_ref2 = this.index) != null ? _ref2 : this.index = 0;
    };

    Collection.prototype.events = {
      'dblclick img': 'open',
      'click #next-button': 'next',
      'click #prev-button': 'prev'
    };

    Collection.prototype.set = function(identifier, options) {
      var index, model, route, _ref, _ref2, _ref3;
      if (options == null) options = {};
      if (options.noSet) return false;
      if (_.isNumeric(identifier)) {
        index = parseInt(identifier);
        model = this.collection.length ? this.collection.at(index) : this.model;
      } else {
        model = this.collection.get(identifier);
        index = this.collection.models.indexOf(model);
        options.noNavigate = false;
      }
      if (!(model && index >= 0)) return false;
      _ref = [model, model, index], this.model = _ref[0], arcs.resource = _ref[1], this.index = _ref[2];
      if (options.trigger) {
        arcs.trigger('arcs:indexchange', index, {
          noSet: true
        });
      }
      if (!options.noRender) this.render();
      route = "" + ((_ref2 = (_ref3 = arcs.collectionData) != null ? _ref3.id : void 0) != null ? _ref2 : this.model.id) + "/" + (this.index + 1);
      if (!options.noNavigate) this.router.navigate(route);
      return true;
    };

    Collection.prototype.next = function() {
      return this.set(this.index + 1, {
        trigger: true
      });
    };

    Collection.prototype.prev = function() {
      return this.set(this.index - 1, {
        trigger: true
      });
    };

    Collection.prototype.open = function() {
      return window.open(this.model.get('url'), '_blank', 'menubar=no');
    };

    Collection.prototype.checkNav = function() {
      if (this.collection.length === this.index + 1) {
        this.$('#next-button').addClass('disabled');
      } else {
        this.$('#next-button').removeClass('disabled');
      }
      if (this.index === 0) {
        return this.$('#prev-button').addClass('disabled');
      } else {
        return this.$('#prev-button').removeClass('disabled');
      }
    };

    Collection.prototype.splitPrompt = function() {
      var _this = this;
      if (this.model.get('mime_type') === 'application/pdf') {
        return new arcs.views.Modal({
          title: "Split into a PDF?",
          subtitle: "We noticed you've uploaded a PDF. If you'd like, " + "we can split the PDF into a collection, where it can be " + "annotated and commented on--page by page.",
          buttons: {
            yes: {
              "class": 'btn-success',
              callback: function() {
                return $.get(arcs.baseURL + 'resources/split_pdf/' + _this.model.id);
              }
            },
            no: {
              text: 'No, leave it alone'
            }
          }
        });
      }
    };

    Collection.prototype.render = function() {
      var mimeInfo, template;
      mimeInfo = arcs.utils.mime.getInfo(this.model.get('mime_type'));
      switch (mimeInfo.type) {
        case 'image':
          template = 'resource/image';
          break;
        case 'document':
          template = 'resource/document';
          break;
        case 'video':
          template = 'resource/video';
          break;
        default:
          template = 'resource/unknown';
      }
      this.$('#resource').html(arcs.tmpl(template, this.model.toJSON()));
      arcs.trigger('arcs:resourceloaded');
      this.$('#resource-details').html(arcs.tmpl('resource/table', this.model.toJSON()));
      if (_.has(arcs, 'collectionData')) {
        this.$('#collection-details').html(arcs.tmpl('resource/collection_table', arcs.collectionData));
      }
      this.checkNav();
      return this;
    };

    return Collection;

  })(Backbone.View);

}).call(this);
