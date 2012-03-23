(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Hotspot = (function(_super) {

    __extends(Hotspot, _super);

    function Hotspot() {
      Hotspot.__super__.constructor.apply(this, arguments);
    }

    Hotspot.prototype.initialize = function() {
      this.collection = new arcs.collections.HotspotMap;
      this.reRender = _.throttle(this.render, 50);
      arcs.bind('arcs:resourceResize', this.reRender, this);
      arcs.bind('arcs:resourceLoaded', this.setup, this);
      return this.collection.bind('add remove reset', this.render, this);
    };

    Hotspot.prototype.setup = function() {
      this.img = this.$el.find('img');
      if (this.img != null) {
        this.startImgAreaSelect();
        return this.collection.fetch();
      }
    };

    Hotspot.prototype.startImgAreaSelect = function(coords) {
      var _this = this;
      if (coords == null) coords = null;
      return this.img.imgAreaSelect({
        handles: true,
        onSelectEnd: function(img, sel) {
          _this.currentHotspot = {
            img: img,
            sel: sel
          };
          return _this.openModal();
        }
      });
    };

    Hotspot.prototype.openModal = function() {
      var $results, modal, search,
        _this = this;
      modal = new arcs.views.Modal({
        title: 'Annotation Sidebar',
        backdrop: false,
        "class": 'hotspot-modal',
        inputs: {
          type: {
            type: 'select',
            options: {
              'Photo': 'photo',
              'Sketch': 'sketch'
            }
          },
          title: true,
          caption: {
            type: 'textarea'
          }
        },
        buttons: {
          save: {
            "class": 'btn success',
            callback: function(vals) {
              vals.resource = $('.result.selected img').data('id');
              _this.saveHotspot(vals);
              return _this.img.imgAreaSelect({
                hide: true
              });
            }
          },
          cancel: function() {
            return _this.img.imgAreaSelect({
              hide: true
            });
          }
        }
      });
      modal.$('.modal-body').append(arcs.tmpl('resource/hotspot_modal'));
      modal.$('.img-wrapper img').live('click', function() {
        $('.result').removeClass('selected');
        return $(this).parents('.result').addClass('selected');
      });
      modal.$('input#url').keyup(function() {
        var val;
        val = $(this).val();
        if (val.substring(0, 7) === 'http://') {
          return $(this).val(val.substring(7));
        }
      });
      $results = $('#hotspot-search-results');
      return search = new arcs.utils.Search({
        container: $('#hotspot-search'),
        success: function() {
          return $results.html(arcs.tmpl('search/grid', {
            results: search.results.toJSON()
          }));
        }
      });
    };

    Hotspot.prototype.saveHotspot = function(data) {
      var hotspot, scaled;
      scaled = this._scaleDown(this.currentHotspot.sel);
      if (data.url) {
        data.link = 'http://' + data.url;
      } else if (data.resource) {
        data.link = 'arcs://' + data.resource;
      } else {
        data.link = null;
      }
      hotspot = new arcs.models.Hotspot({
        resource_id: arcs.resource.id,
        type: data.type,
        caption: data.caption,
        title: data.title,
        link: data.link,
        x1: scaled.x1,
        x2: scaled.x2,
        y1: scaled.y1,
        y2: scaled.y2
      });
      hotspot.save();
      return this.collection.add(hotspot);
    };

    Hotspot.prototype._scaleDown = function(cds) {
      cds.x1 /= this.img.width();
      cds.y1 /= this.img.height();
      cds.x2 /= this.img.width();
      cds.y2 /= this.img.height();
      return cds;
    };

    Hotspot.prototype._scaleUp = function(cds) {
      cds.x1 *= this.img.width();
      cds.y1 *= this.img.height();
      cds.x2 *= this.img.width();
      cds.y2 *= this.img.height();
      return cds;
    };

    Hotspot.prototype.render = function() {
      var $annotations, $hotspots, data, json, m, _i, _len, _ref;
      $hotspots = $('#hotspots-wrapper');
      $annotations = $('#annotations-wrapper');
      $hotspots.html('');
      $annotations.html('');
      json = {
        hotspots: []
      };
      _ref = this.collection.models;
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        m = _ref[_i];
        data = this._scaleUp(m.toJSON());
        data.left = data.x1;
        data.width = data.x2 - data.x1;
        data.top = data.y1;
        data.height = data.y2 - data.y1;
        if ((data.link != null) && data.link.substring(0, 7) === 'arcs://') {
          data.link = arcs.baseURL + 'resource/' + data.link.substring(7);
        }
        json.hotspots.push(data);
      }
      $hotspots.html(arcs.tmpl('resource/hotspots', json));
      $annotations.html(arcs.tmpl('resource/annotations', json));
      return this;
    };

    return Hotspot;

  })(Backbone.View);

}).call(this);
