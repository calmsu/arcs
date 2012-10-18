(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Keyword = (function(_super) {

    __extends(Keyword, _super);

    function Keyword() {
      Keyword.__super__.constructor.apply(this, arguments);
    }

    Keyword.prototype.events = {
      'keydown #keyword-btn': 'saveKeyword',
      'click .keyword-remove-btn': 'deleteKeyword'
    };

    Keyword.prototype.initialize = function() {
      var _this = this;
      this.collection = new arcs.collections.KeywordList;
      arcs.bus.on('indexChange', function() {
        return _this.collection.fetch();
      });
      this.collection.on('add remove reset sync', this.render, this);
      arcs.utils.autocomplete({
        sel: '#keyword-btn',
        source: arcs.complete('keywords/complete')
      });
      return this.collection.fetch();
    };

    Keyword.prototype.saveKeyword = function(e) {
      var $input, keyword;
      if (e.keyCode !== 13) return;
      e.preventDefault();
      $input = this.$el.find('input#keyword-btn');
      keyword = new arcs.models.Keyword({
        resource_id: arcs.resource.id,
        keyword: $input.val()
      });
      $input.val('');
      keyword.save();
      this.collection.add(keyword);
      return false;
    };

    Keyword.prototype.deleteKeyword = function(e) {
      var $keyword, keyword,
        _this = this;
      $keyword = $(e.target).parent().find('.keyword-link');
      keyword = this.collection.get($keyword.data('id'));
      if (!keyword) return;
      return arcs.confirm('Are you sure?', "This keyword will be deleted.", function() {
        return keyword.destroy();
      });
    };

    Keyword.prototype.render = function() {
      this.$('#keywords-wrapper').html(arcs.tmpl('viewer/keywords', {
        keywords: this.collection.toJSON()
      }));
      return this;
    };

    return Keyword;

  })(Backbone.View);

}).call(this);
