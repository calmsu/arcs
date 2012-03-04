(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Resource = (function(_super) {

    __extends(Resource, _super);

    function Resource() {
      Resource.__super__.constructor.apply(this, arguments);
    }

    Resource.prototype.el = $('#resource-wrapper');

    Resource.prototype.initialize = function() {
      var _this = this;
      arcs.bind('resourceChange', function() {
        arcs.utils.hash.set(_this.index + 1);
        return _this.render();
      });
      arcs.discussionView = new arcs.views.Discussion({
        el: $('#discussion')
      });
      arcs.tagView = new arcs.views.Tag({
        el: $('#information')
      });
      arcs.hotspotView = new arcs.views.Hotspot({
        el: $('#resource')
      });
      arcs.toolbarView = new arcs.views.Toolbar({
        el: $('#toolbar')
      });
      this.index = (arcs.utils.hash.get() || 1) - 1;
      this.setResource(this.index) || this.render();
      this._setupCarousel(this.index);
      arcs.utils.keys.add('left', false, this.prevResource, this);
      arcs.utils.keys.add('right', false, this.nextResource, this);
      this.model.bind('change', function() {
        return _this.render;
      });
      this.model.bind('destroy', function() {
        return _this.render;
      });
      if (this.model.get('first_req')) return this.firstReq();
    };

    Resource.prototype.events = {
      'dblclick img': 'openFullScreen',
      'click #next-button': 'nextResource',
      'click #prev-button': 'prevResource'
    };

    Resource.prototype._setupCarousel = function(index) {
      var _this = this;
      $('#carousel').elastislide({
        imageW: 100,
        onClick: function($item) {
          var id;
          id = $item.find('img').attr('data-id');
          return _this.setResourceById(id);
        }
      });
      return this.setCarousel(index);
    };

    Resource.prototype.openFullScreen = function() {
      return window.open(this.model.get('url'), '_blank', 'menubar=no');
    };

    Resource.prototype.nextResource = function() {
      if (this.collection.length > this.index + 1) {
        this.index += 1;
        this.swapModel(this.collection.at(this.index));
        return this.setCarousel(this.index);
      }
    };

    Resource.prototype.prevResource = function() {
      if (this.index > 0) {
        this.index -= 1;
        this.swapModel(this.collection.at(this.index));
        return this.setCarousel(this.index);
      }
    };

    Resource.prototype.setResource = function(index) {
      if (this.collection.length > index + 1 && index >= 0) {
        this.swapModel(this.collection.at(index));
        return this.index = index;
      }
    };

    Resource.prototype.setResourceById = function(id) {
      return this.swapModel(this.collection.get(id));
    };

    Resource.prototype.setIndex = function() {
      return this.index = this.collection.indexOf(this.model);
    };

    Resource.prototype.setCarousel = function(index) {
      return $('#carousel').elastislide('slideToIndex', index);
    };

    Resource.prototype.checkNavigation = function() {
      if (this.collection.length === this.index + 1) {
        $('#next-button').addClass('disabled');
      } else {
        $('#next-button').removeClass('disabled');
      }
      if (this.index === 0) {
        return $('#prev-button').addClass('disabled');
      } else {
        return $('#prev-button').removeClass('disabled');
      }
    };

    Resource.prototype.swapModel = function(model) {
      this.model = model;
      this.setIndex();
      arcs.resource = model;
      return arcs.trigger('resourceChange');
    };

    Resource.prototype.setThumbSelected = function() {
      var $carousel;
      $carousel = $('#carousel');
      $carousel.find('.thumb').removeClass('selected');
      return $carousel.find(".thumb[data-id=" + this.model.id + "]").addClass('selected');
    };

    Resource.prototype.firstReq = function() {
      var _this = this;
      if (this.model.get('mime_type') === 'application/pdf') {
        return new arcs.views.Modal({
          title: "Split into a PDF?",
          subtitle: "We noticed you've uploaded a PDF. If you'd like, " + "we can split the PDF into a collection, where it can be " + "annotated and commented on--page by page.",
          buttons: {
            yes: function() {
              return $.get(arcs.baseURL + 'resources/pdfSplit/' + _this.model.id);
            },
            no: function() {}
          }
        });
      }
    };

    Resource.prototype.render = function() {
      var $ctable, $resource, $table, type;
      $resource = this.$el.find('#resource');
      $table = this.$el.find('#resource-details');
      $ctable = this.$el.find('#collection-details');
      $resource.html('');
      type = arcs.utils.mime.getInfo(this.model.get('mime_type')).type;
      if (type === 'image') {
        $resource.html(arcs.tmpl('resource/image', this.model.toJSON()));
      } else if (type === 'document') {
        $resource.html(arcs.tmpl('resource/document', this.model.toJSON()));
      } else {
        $resource.html('Unknown resource type.');
      }
      arcs.trigger('resourceLoaded');
      $table.html(arcs.tmpl('resource/table', this.model.toJSON()));
      if (this.collection.length) {
        $ctable.html(arcs.tmpl('resource/collection_table', arcs.collectionData));
      }
      this.checkNavigation();
      this.setThumbSelected();
      return this;
    };

    return Resource;

  })(Backbone.View);

}).call(this);
