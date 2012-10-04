(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Carousel = (function(_super) {

    __extends(Carousel, _super);

    function Carousel() {
      Carousel.__super__.constructor.apply(this, arguments);
    }

    Carousel.prototype.options = {
      index: 0,
      nthumbs: 30
    };

    Carousel.prototype.initialize = function() {
      var _this = this;
      arcs.bus.on('indexChange', this.slideTo, this);
      arcs.bus.on('indexChange', this.setSelected, this);
      this.render();
      this.$el.elastislide({
        imageW: 100,
        onClick: function($item) {
          return arcs.bus.trigger('indexChange', $item.index(), {
            noSlide: true
          });
        },
        onSlide: function(first, last) {
          if (!_.isNaN(last)) {
            if (first > 1) {
              setTimeout(function() {
                return _this.$el.find('.es-nav-prev').show();
              }, 50);
            }
            if (last > _this.options.nthumbs - 10) return _this._addThumbs();
          }
        }
      });
      return this.slideTo(this.options.index);
    };

    Carousel.prototype.events = {
      'click li': 'onClick'
    };

    Carousel.prototype.onClick = function(e) {
      return arcs.bus.trigger('indexChange', $(e.target).parent().index(), {
        noSlide: true
      });
    };

    Carousel.prototype.slideTo = function(index, options) {
      if (options == null) options = {};
      if (this.$('li').length < index) {
        this._addThumbs(index + 1 - this.$('li').length);
      }
      if (!options.noSlide) return this.$el.elastislide('slideToIndex', index);
    };

    Carousel.prototype.setSelected = function(index) {
      var img;
      this.$('.thumb.selected').removeClass('selected');
      img = this.$('.thumb').get(index);
      return $(img).addClass('selected');
    };

    Carousel.prototype._addThumbs = function(n) {
      var $thumbs, additions, m;
      if (n == null) n = 30;
      additions = this.collection.models.slice(this.options.nthumbs, this.options.nthumbs + n);
      $thumbs = $(this._tmpl({
        resources: (function() {
          var _i, _len, _results;
          _results = [];
          for (_i = 0, _len = additions.length; _i < _len; _i++) {
            m = additions[_i];
            _results.push(m.toJSON());
          }
          return _results;
        })(),
        offset: this.options.nthumbs
      }));
      this.$el.elastislide('add', $thumbs.filter('li'));
      this.options.nthumbs += n;
      return this.delegateEvents();
    };

    Carousel.prototype.render = function() {
      return this.$('ul').html(this._tmpl({
        resources: _.first(this.collection.toJSON(), this.options.nthumbs),
        offset: 0
      }));
    };

    Carousel.prototype._tmpl = function(data) {
      return arcs.tmpl('viewer/carousel', data);
    };

    return Carousel;

  })(Backbone.View);

}).call(this);
