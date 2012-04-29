(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Modal = (function(_super) {

    __extends(Modal, _super);

    function Modal() {
      Modal.__super__.constructor.apply(this, arguments);
    }

    Modal.prototype.options = {
      draggable: true,
      backdrop: true,
      keyboard: true,
      show: true,
      "class": '',
      title: 'No Title',
      subtitle: null,
      template: 'ui/modal',
      inputs: {},
      buttons: {}
    };

    Modal.prototype.initialize = function() {
      var $sel, name, options, _ref, _ref2;
      $('#modal').remove();
      $('.modal-backdrop').remove();
      $('body').append(arcs.tmpl('ui/modal_wrapper'));
      this.el = this.$el = $('#modal');
      if (this.options["class"]) this.$el.addClass(this.options["class"]);
      this.$el.html(arcs.tmpl(this.options.template, this.options));
      _ref = this.options.inputs;
      for (name in _ref) {
        options = _ref[name];
        $sel = this.$("#modal-" + name + "-input");
        if (options.complete || options.multicomplete) {
          arcs.utils.autocomplete({
            sel: $sel,
            multiple: !!options.multicomplete,
            source: (_ref2 = options.multicomplete) != null ? _ref2 : options.complete
          });
        }
      }
      if (this.options.draggable) {
        this.$el.draggable({
          handle: this.$('.modal-header')
        });
        this.$('.modal-header').css('cursor', 'move');
      }
      this.$el.modal({
        backdrop: this.options.backdrop,
        keyboard: this.options.keyboard,
        show: this.options.show
      });
      return this.bindButtons();
    };

    Modal.prototype.hide = function() {
      return this.$el.modal('hide');
    };

    Modal.prototype.show = function() {
      return this.$el.modal('show');
    };

    Modal.prototype.isOpen = function() {
      return this.$el.is(':visible');
    };

    Modal.prototype.validate = function() {
      var name, options, required, values, _i, _len, _ref;
      this.$('#validation-error').hide();
      this.$('.error').removeClass('error');
      values = this.getValues();
      required = [];
      _ref = this.options.inputs;
      for (name in _ref) {
        options = _ref[name];
        if (options.required) {
          if (!values[name].replace(/\s/g, '').length) required.push(name);
        }
      }
      if (!required.length) return true;
      for (_i = 0, _len = required.length; _i < _len; _i++) {
        name = required[_i];
        this.$("#modal-" + name + "-input").addClass('error');
        this.$("label[for='modal-" + name + "']").addClass('error');
      }
      this.$('#validation-error').show().html('Looks like you missed a required field.');
      return false;
    };

    Modal.prototype.getValues = function() {
      var name, values;
      values = {};
      for (name in this.options.inputs) {
        values[name] = this.$("#modal-" + name + "-input").val();
      }
      return values;
    };

    Modal.prototype.bindButtons = function() {
      var name, _results,
        _this = this;
      _results = [];
      for (name in this.options.buttons) {
        _results.push(this.$("button#modal-" + name + "-button").click(function(e) {
          var callback, cb, context, options, valid, _ref, _ref2, _ref3;
          name = e.target.id.match(/modal-([\w-]+)-button/)[1];
          options = _this.options.buttons[name];
          if (_.isFunction(options)) {
            cb = options;
          } else {
            _ref3 = [(_ref = options.callback) != null ? _ref : (function() {}), (_ref2 = options.context) != null ? _ref2 : window], callback = _ref3[0], context = _ref3[1];
            cb = _.bind(callback, context);
          }
          valid = options.validate ? _this.validate() : true;
          if (valid) cb(_this.getValues());
          if (!(((options.close != null) && options.close) || !valid)) {
            return _this.hide();
          }
        }));
      }
      return _results;
    };

    return Modal;

  })(Backbone.View);

}).call(this);
