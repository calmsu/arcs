var __hasProp = Object.prototype.hasOwnProperty, __extends = function(child, parent) {
  for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; }
  function ctor() { this.constructor = child; }
  ctor.prototype = parent.prototype;
  child.prototype = new ctor;
  child.__super__ = parent.prototype;
  return child;
}, __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
arcs.views.Hotspot = (function() {
  __extends(Hotspot, Backbone.View);
  function Hotspot() {
    Hotspot.__super__.constructor.apply(this, arguments);
  }
  Hotspot.prototype.initialize = function() {
    this.collection = new arcs.collections.HotspotMap;
    this.reRender = _.throttle(this.render, 50);
    $(window).resize(__bind(function() {
      return arcs.trigger('resourceResize');
    }, this));
    arcs.bind('resourceResize', __bind(function() {
      return this.reRender();
    }, this));
    arcs.bind('resourceLoaded', __bind(function() {
      return this.setup();
    }, this));
    this.collection.bind('add', __bind(function() {
      return this.render();
    }, this));
    return this.collection.bind('remove', __bind(function() {
      return this.render();
    }, this));
  };
  Hotspot.prototype.setup = function() {
    this.img = this.el.find('img');
    if (this.img != null) {
      this.startImgAreaSelect();
      return this.update();
    }
  };
  Hotspot.prototype.startImgAreaSelect = function(coords) {
    if (coords == null) {
      coords = null;
    }
    return this.img.imgAreaSelect({
      handles: true,
      onSelectEnd: __bind(function(img, sel) {
        this.currentHotspot = {
          img: img,
          sel: sel
        };
        return this.openModal();
      }, this)
    });
  };
  Hotspot.prototype.openModal = function() {
    var $results, search;
    arcs.utils.modal({
      template: arcs.templates.hotspotModal,
      backdrop: false,
      draggable: true,
      handle: '#drag-handle',
      inputs: ['caption', 'title', 'type', 'url'],
      buttons: {
        save: {
          callback: function(vals) {
            vals.resource = $('.result.selected img').attr('data-id');
            this.saveHotspot(vals);
            arcs.log(vals);
            return this.img.imgAreaSelect({
              hide: true
            });
          },
          context: this
        },
        cancel: {
          callback: function() {
            return this.img.imgAreaSelect({
              hide: true
            });
          },
          context: this
        }
      }
    });
    $('.result img').live('click', function() {
      $('.result').removeClass('selected');
      return $(this).parent().addClass('selected');
    });
    $('input#url').keyup(function() {
      var val;
      val = $(this).val();
      if (val.substring(0, 7) === 'http://') {
        return $(this).val(val.substring(7));
      }
    });
    $results = $('#hotspot-search-results');
    return search = new arcs.utils.Search({
      container: $('#hotspot-search'),
      success: __bind(function() {
        return $results.html(Mustache.render(arcs.templates.resultsGrid, {
          results: search.results.toJSON()
        }));
      }, this)
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
  Hotspot.prototype.update = function() {
    return this.collection.fetch({
      success: __bind(function() {
        return this.render();
      }, this)
    });
  };
  Hotspot.prototype.render = function() {
    var $annotations, $hotspots, data, hotspot, json, _i, _len, _ref;
    $hotspots = $('#hotspots-wrapper');
    $annotations = $('#annotations-wrapper');
    $hotspots.html('');
    $annotations.html('');
    json = {
      hotspots: []
    };
    _ref = this.collection.models;
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      hotspot = _ref[_i];
      data = this._scaleUp(hotspot.toJSON());
      data.left = data.x1;
      data.width = data.x2 - data.x1;
      data.top = data.y1;
      data.height = data.y2 - data.y1;
      if ((data.link != null) && data.link.substring(0, 7) === 'arcs://') {
        data.link = arcs.baseURL + 'resource/' + data.link.substring(7);
      }
      json.hotspots.push(data);
    }
    $hotspots.html(Mustache.render(arcs.templates.hotspot, json));
    return $annotations.html(Mustache.render(arcs.templates.annotation, json));
  };
  return Hotspot;
})();