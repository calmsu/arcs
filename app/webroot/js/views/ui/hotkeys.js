(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Hotkeys = (function(_super) {

    __extends(Hotkeys, _super);

    function Hotkeys() {
      Hotkeys.__super__.constructor.apply(this, arguments);
    }

    Hotkeys.prototype.initialize = function() {
      var ctrl;
      ctrl = navigator.appVersion.indexOf('Mac') !== -1 ? '⌘' : 'ctrl';
      if (!$('.hotkeys-modal').length) {
        $('body').append(arcs.tmpl(this.options.template, {
          ctrl: ctrl
        }));
      }
      this.el = this.$el = $('.hotkeys-modal');
      $('.hotkeys-modal').modal({
        backdrop: false
      });
      return this.delegateEvents();
    };

    Hotkeys.prototype.events = {
      'click .hotkeys-close': 'close'
    };

    Hotkeys.prototype.close = function() {
      return this.el.remove();
    };

    return Hotkeys;

  })(Backbone.View);

}).call(this);
