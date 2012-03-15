(function() {

  arcs.utils.Modal = (function() {

    function Modal(options) {
      var defaults;
      defaults = {
        template: '',
        templateValues: {},
        draggable: false,
        handle: null,
        backdrop: true,
        "class": null,
        inputs: [],
        buttons: {}
      };
      this.options = _.extend(defaults, options);
      this._setEl();
      if (this.options["class"] != null) this.el.addClass(this.options["class"]);
      if (this.options.draggable) {
        this.el.draggable({
          handle: this.options.handle
        });
      }
      this.el.modal({
        backdrop: this.options.backdrop,
        keyboard: true,
        show: false
      });
      if (this.el.attr('data-first') !== 'false') {
        this.el.attr('data-first', 'true');
      }
      this.show();
      this._bindButtons();
    }

    Modal.prototype._setEl = function() {
      if (!$('#modal').length) $('body').append(arcs.tmpl('ui/modal_wrapper'));
      return this.el = $('#modal');
    };

    Modal.prototype.hide = function() {
      return this.el.modal('hide');
    };

    Modal.prototype.show = function() {
      this.el.html(arcs.tmpl(this.options.template, this.options.templateValues));
      this.el.modal('show');
      if (this.el.attr('data-first') === 'true') {
        this.el.css('right', '-400px').animate({
          right: '0px'
        });
        return this.el.attr('data-first', 'false');
      }
    };

    Modal.prototype.visible = function() {
      return this.el.is(':visible');
    };

    Modal.prototype.values = function() {
      var id, vals, _i, _len, _ref;
      vals = {};
      if (this.options.inputs.length) {
        _ref = this.options.inputs;
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
          id = _ref[_i];
          vals[id] = this.el.find("#" + id).val();
        }
      }
      return vals;
    };

    Modal.prototype._bindButtons = function() {
      var id, _i, _len, _ref, _results,
        _this = this;
      _ref = _.keys(this.options.buttons);
      _results = [];
      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        id = _ref[_i];
        _results.push(this.el.find("a#" + id + ", button#" + id).one('click', function(e) {
          var button, callback, context, _ref2, _ref3;
          button = _this.options.buttons[e.target.id];
          if (_.isFunction(button)) {
            button(_this.values());
          } else {
            _ref3 = [button.callback, (_ref2 = button.context) != null ? _ref2 : null], callback = _ref3[0], context = _ref3[1];
            if (context != null) callback = _.bind(callback, context);
            callback(_this.values());
          }
          if (!button.keepOpen) return _this.hide();
        }));
      }
      return _results;
    };

    return Modal;

  })();

}).call(this);
