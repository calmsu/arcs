(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Home = (function(_super) {

    __extends(Home, _super);

    function Home() {
      Home.__super__.constructor.apply(this, arguments);
    }

    Home.prototype.initialize = function() {
      var _this = this;
      this.search = new arcs.utils.Search({
        container: $('.search-wrapper'),
        run: false,
        success: function() {
          return location.href = arcs.baseURL + 'search/' + _this.search.query;
        }
      });
      return this.renderDetails($('details:first'));
    };

    Home.prototype.events = {
      'click summary': 'onClick'
    };

    Home.prototype.onClick = function(e) {
      return this.renderDetails($(e.currentTarget).parent());
    };

    Home.prototype.renderDetails = function(el) {
      var order,
        _this = this;
      order = _.shuffle(['title', 'modified']).pop();
      return $.getJSON(arcs.baseURL + ("resources/search?n=12&order=" + order), function(data) {
        return el.children('div').html(arcs.tmpl('home/details', {
          resources: data
        }));
      });
    };

    return Home;

  })(Backbone.View);

}).call(this);
