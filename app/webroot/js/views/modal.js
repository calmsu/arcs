(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Modal = (function(_super) {

    __extends(Modal, _super);

    function Modal() {
      Modal.__super__.constructor.apply(this, arguments);
    }

    Modal.prototype.options = {
      draggable: false,
      dragHandle: null,
      backdrop: true,
      keyboard: true,
      show: true,
      "class": '',
      title: 'No Title',
      subtitle: null,
      template: 'ui/modal',
      templateValues: {},
      inputs: {},
      buttons: {}
    };

    Modal.prototype.initialize = function() {
      if (!$('#modal').length) $('body').append(arcs.tmpl('ui/modal_wrapper'));
      this.el = this.$el = $('#modal');
      this.$el.addClass(this.options["class"]);
      this.$el.html(arcs.tmpl(this.options.template(this.options)));
      _.each(this.options.inputs, function(opts, k) {
        var $sel, _ref;
        $sel = this.$("#modal-" + k + "-input");
        if (opts.complete || opts.multicomplete) {
          return arcs.utils.autocomplete({
            sel: $sel,
            multiple: !!opts.multicomplete,
            source: (_ref = opts.multicomplete) != null ? _ref : opts.complete
          });
        }
      });
      if (this.options.draggable) {
        this.$el.draggable({
          handle: this.options.dragHandle
        });
      }
      this.$el.modal({
        backdrop: this.options.backdrop,
        keyboard: this.options.keyboard,
        show: this.options.show
      });
      return this._bindButtons();
    };

    Modal.prototype.hide = function() {
      return this.$el.modal('hide');
    };

    Modal.prototype.show = function() {
      return this.$el.modal('show');
    };

    Modal.prototype._getValues = function() {
      var key, values, _i, _len, _ref;
      values = {};
      _ref = _.keys(this.options.inputs);
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        key = _ref[_i];
        values[key] = this.$("#modal-" + key + "-input").val();
      }
      return values;
    };

    Modal.prototype._bindButtons = function() {
      var key, _i, _len, _ref, _results,
        _this = this;
      _ref = _.keys(this.options.buttons);
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        key = _ref[_i];
        _results.push(this.$("button#modal-" + key + "-button").one('click', function(e) {
          var button, callback, context, _ref2, _ref3;
          key = e.target.id.match(/modal-(\w+)-button/)[1];
          button = _this.options.buttons[key];
          if (_.isFunction(button)) {
            button(_this._getValues());
          } else {
            _ref3 = [button.callback, (_ref2 = button.context) != null ? _ref2 : window], callback = _ref3[0], context = _ref3[1];
            _.bind(callback, context)(_this._getValues());
          }
          if (!((button.close != null) || button.close)) return _this.hide();
        }));
      }
      return _results;
    };

    return Modal;

  })(Backbone.View);

}).call(this);
