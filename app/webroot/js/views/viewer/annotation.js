(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Annotation = (function(_super) {

    __extends(Annotation, _super);

    function Annotation() {
      Annotation.__super__.constructor.apply(this, arguments);
    }

    Annotation.prototype.initialize = function() {
      var _this = this;
      this.collection = new arcs.collections.AnnotationList;
      this.collection.on('add sync reset remove', this.render, this);
      arcs.bus.on('resourceLoaded', this.onLoad, this);
      arcs.bus.on('resourceResize', this.render, this);
      arcs.bus.on('indexChange', this.clear, this);
      this.visible = true;
      return $('#annotation-vis-btn').on('click', function(e) {
        return _this.toggleVisibility(e);
      });
    };

    Annotation.prototype.events = {
      'mouseenter .annotation': 'annoMouseenter',
      'mouseleave .annotation': 'annoMouseleave',
      'mouseenter .hotspot': 'hotMouseenter',
      'hover .annotation a': 'annoMouseenter',
      'click .remove-btn': 'removeAnnotation'
    };

    Annotation.prototype.onLoad = function() {
      this.img = $('#resource img');
      this.setupSelection();
      return this.collection.fetch();
    };

    Annotation.prototype.annoMouseenter = function(e) {
      var $li, id;
      if (e.target.tagName === 'A') {
        $li = $(e.target).parent();
      } else {
        $li = $(e.target);
      }
      id = $li.data('id');
      $('.hotspot').removeClass('active');
      return $(".hotspot[data-id='" + id + "']").addClass('active');
    };

    Annotation.prototype.annoMouseleave = function() {
      return $('.hotspot').removeClass('active');
    };

    Annotation.prototype.hotMouseenter = function(e) {
      var $el, anno;
      $el = $(e.target);
      anno = this.collection.get($el.data('id'));
      $el.popover({
        title: arcs.tmpl('viewer/popover_title', {
          type: anno.getType()
        }),
        content: arcs.tmpl('viewer/popover', anno.toJSON())
      });
      return $el.popover('show');
    };

    Annotation.prototype.setupSelection = function(coords) {
      var _this = this;
      if (coords == null) coords = null;
      if (this.ias != null) this.ias.remove();
      return this.ias = this.img.imgAreaSelect({
        instance: true,
        handles: true,
        onSelectEnd: function(img, sel) {
          if (!arcs.user.get('loggedIn')) return arcs.needsLogin();
          return _this.openAnnotator();
        }
      });
    };

    Annotation.prototype.toggleVisibility = function(e) {
      var $btn, msg;
      this.visible = !this.visible;
      msg = "Annotations are " + (this.visible ? 'visible' : 'hidden');
      $btn = $('#annotation-vis-btn');
      $btn.toggleClass('opaque').attr('data-original-title', msg).tooltip('show');
      if (this.visible) return this.collection.fetch();
      return $('#hotspots-wrapper').html('');
    };

    Annotation.prototype.removeAnnotation = function(e) {
      var $hotspot, anno,
        _this = this;
      e.stopPropagation();
      $hotspot = $(e.target).parent();
      $hotspot.popover('hide');
      anno = this.collection.get($hotspot.data('id'));
      if (!anno) return;
      arcs.confirm('Are you sure?', "This <b>" + (anno.getType().toLowerCase()) + "</b> will be deleted.", function() {
        return anno.destroy();
      });
      return false;
    };

    Annotation.prototype.openAnnotator = function() {
      var _ref,
        _this = this;
      if ((_ref = this.annotator) != null ? _ref.isOpen() : void 0) return;
      this.annotator = new arcs.views.Modal({
        title: 'New Annotation',
        subtitle: arcs.tmpl('viewer/annotator'),
        backdrop: false,
        "class": 'annotator',
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function() {
              var data;
              data = {
                relation: _this.annotator.$('.result.selected img').data('id'),
                transcript: _this.annotator.$('textarea#transcript').val(),
                url: _this.annotator.$('input#url').val()
              };
              if (!_.any(data)) return;
              _this.create(data);
              _this.ias.cancelSelection();
              return delete _this.annotator;
            }
          },
          cancel: function() {
            _this.ias.cancelSelection();
            return delete _this.annotator;
          }
        }
      });
      this.annotator.$el.on('click', '.result img', function() {
        $('.result').removeClass('selected');
        return $(this).parents('.result').addClass('selected');
      });
      this.annotator.$('input#url').keyup(function() {
        var val;
        val = $(this).val();
        if (val.substring(0, 7) === 'http://') {
          return $(this).val(val.substring(7));
        }
      });
      return this.search = new arcs.utils.Search({
        container: $('.mini-search'),
        success: function() {
          return $('.mini-search-results').html(arcs.tmpl('search/grid', {
            results: _this.search.results.toJSON()
          }));
        }
      });
    };

    Annotation.prototype.create = function(data) {
      var anno;
      anno = new arcs.models.Annotation({
        resource_id: arcs.resource.id
      });
      if (data.relation) {
        anno.set('relation', data.relation);
      } else if (data.transcript) {
        anno.set('transcript', data.transcript);
      } else {
        anno.set('url', 'http://' + data.url);
      }
      if (this.ias != null) {
        anno.setScaled(this.ias.getSelection(), this.img.height(), this.img.width());
      }
      anno.save();
      anno.set('id', _.uniqueId());
      return this.collection.add(anno);
    };

    Annotation.prototype.clear = function() {
      $('#annotations-wrapper').html('');
      return $('#hotspots-wrapper').html('');
    };

    Annotation.prototype.render = function() {
      var annos,
        _this = this;
      annos = {
        annotations: this.collection.map(function(m) {
          var rid, _ref, _ref2;
          if (rid = m.get('relation')) {
            m.set('relation', (((_ref = _this.search) != null ? _ref.results.get(rid) : void 0) || ((_ref2 = _this.collection.relations) != null ? _ref2.get(rid) : void 0)).toJSON());
          }
          return _.extend(m.toJSON(), m.scaleTo(_this.img.height(), _this.img.width()));
        }),
        offset: $('#resource img').offset().left - $('#resource').offset().left
      };
      $('#annotations-wrapper').html(arcs.tmpl('viewer/annotations', annos));
      if (this.visible) {
        $('#hotspots-wrapper').html(arcs.tmpl('viewer/hotspots', annos));
      }
      return this;
    };

    return Annotation;

  })(Backbone.View);

}).call(this);
