(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.ContextMenu = (function(_super) {

    __extends(ContextMenu, _super);

    function ContextMenu() {
      ContextMenu.__super__.constructor.apply(this, arguments);
    }

    ContextMenu.prototype.events = {
      'click *': 'hide'
    };

    ContextMenu.prototype.options = {
      options: {
        'Example option': function() {},
        'Another option': function() {}
      },
      context: window
    };

    ContextMenu.prototype.initialize = function() {
      $('.context-menu').remove();
      $('body').append(arcs.tmpl('ui/context_menu', {
        options: this.options.options
      }));
      this.menu = $('.context-menu');
      return this.addEvents();
    };

    ContextMenu.prototype.show = function(e) {
      $(e.currentTarget).click();
      this.menu.css({
        position: 'absolute',
        top: e.pageY + 'px',
        left: e.pageX + 'px'
      });
      this.menu.show();
      e.preventDefault();
      return false;
    };

    ContextMenu.prototype.addEvents = function() {
      var boundCb, cb, opt, _ref;
      this.events["contextmenu " + this.options.filter] = 'show';
      _ref = this.options.options;
      for (opt in _ref) {
        cb = _ref[opt];
        if (this.options.context[cb] == null) continue;
        boundCb = _.bind(this.options.context[cb], this.options.context);
        this.events["click #context-menu-option-" + (opt.replace(/\s/g, '-'))] = boundCb;
      }
      return this.delegateEvents();
    };

    ContextMenu.prototype.hide = function(e) {
      return this.menu.hide();
    };

    return ContextMenu;

  })(Backbone.View);

}).call(this);
