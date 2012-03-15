(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Keyword = (function(_super) {

    __extends(Keyword, _super);

    function Keyword() {
      this.keydownDelegate = __bind(this.keydownDelegate, this);
      Keyword.__super__.constructor.apply(this, arguments);
    }

    Keyword.prototype.events = {
      'keydown #keyword-btn': 'keydownDelegate'
    };

    Keyword.prototype.initialize = function() {
      var _this = this;
      this.collection = new arcs.collections.KeywordList;
      arcs.bind('resourceChange', function() {
        return _this.update();
      });
      _.bindAll(this, 'render');
      this.collection.on('add remove', this.render, this);
      arcs.utils.autocomplete({
        sel: '#keyword-btn',
        source: arcs.utils.complete.keyword()
      });
      return this.update();
    };

    Keyword.prototype.keydownDelegate = function(e) {
      if (e.keyCode === 13) {
        this.saveKeyword();
        e.preventDefault();
        return false;
      }
    };

    Keyword.prototype.saveKeyword = function() {
      var $input, keyword;
      $input = this.$el.find('input#keyword-btn');
      keyword = new arcs.models.Keyword({
        resource_id: arcs.resource.id,
        keyword: $input.val()
      });
      $input.val('');
      keyword.save();
      return this.collection.add(keyword);
    };

    Keyword.prototype.update = function() {
      var _this = this;
      return this.collection.fetch({
        success: function() {
          return _this.render();
        }
      });
    };

    Keyword.prototype.render = function() {
      var $keywords;
      $keywords = $('#keywords-wrapper');
      $keywords.html(arcs.tmpl('resource/keywords', {
        keywords: this.collection.toJSON()
      }));
      return this;
    };

    return Keyword;

  })(Backbone.View);

}).call(this);
