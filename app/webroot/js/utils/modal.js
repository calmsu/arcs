var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
arcs.utils.modal = function(options) {
  var $modal, defaults, id, _i, _len, _ref;
  defaults = {
    template: '',
    templateValues: {},
    draggable: false,
    handle: null,
    backdrop: true,
    inputs: [],
    buttons: {
      save: {
        callback: function(vals, $modal) {
          return $modal.modal('hide');
        },
        context: this,
        closeAfter: true
      }
    }
  };
  options = _.extend(defaults, options);
  if (!(options.buttons.cancel != null)) {
    options.buttons.cancel = {
      closeAfter: true
    };
  }
  if (!$('#modal').length) {
    $('body').append(arcs.templates.modalWrapper);
  }
  $modal = $('#modal');
  $modal.modal({
    backdrop: options.backdrop
  });
  $modal.html(Mustache.render(options.template, options.templateValues));
  $modal.modal('show');
  if (options.draggable) {
    $modal.draggable({
      handle: options.handle
    });
  }
  _ref = _.keys(options.buttons);
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    id = _ref[_i];
    $modal.find("#" + id).one('click', __bind(function(e) {
      var button, callback, closeAfter, context, id_, vals, _j, _len2, _ref2, _ref3, _ref4, _ref5;
      vals = {};
      if (options.inputs.length) {
        _ref2 = options.inputs;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          id_ = _ref2[_j];
          vals[id_] = $modal.find("#" + id_).val();
        }
      }
      button = options.buttons[e.target.id];
      if (typeof button === 'function') {
        callback = button;
        context = this;
        closeAfter = true;
      } else {
        context = (_ref3 = button.context) != null ? _ref3 : this;
        callback = (_ref4 = button.callback) != null ? _ref4 : function() {};
        closeAfter = (_ref5 = button.closeAfter) != null ? _ref5 : true;
      }
      callback = _.bind(callback, context);
      callback(vals, $modal);
      if (closeAfter) {
        return $modal.modal('hide');
      }
    }, this));
  }
  return $modal;
};