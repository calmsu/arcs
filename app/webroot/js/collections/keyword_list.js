(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.collections.KeywordList = (function(_super) {

    __extends(KeywordList, _super);

    function KeywordList() {
      KeywordList.__super__.constructor.apply(this, arguments);
    }

    KeywordList.prototype.model = arcs.models.Keyword;

    KeywordList.prototype.url = function() {
      return arcs.baseURL + "resources/keywords/" + arcs.resource.id;
    };

    KeywordList.prototype.parse = function(response) {
      var k, keywords, r, _i, _len;
      keywords = (function() {
        var _i, _len, _results;
        _results = [];
        for (_i = 0, _len = response.length; _i < _len; _i++) {
          r = response[_i];
          _results.push(r.Keyword);
        }
        return _results;
      })();
      for (_i = 0, _len = keywords.length; _i < _len; _i++) {
        k = keywords[_i];
        k.link = arcs.baseURL + "search/" + encodeURIComponent("keyword: '" + k.keyword + "'");
      }
      return keywords;
    };

    return KeywordList;

  })(Backbone.Collection);

}).call(this);
