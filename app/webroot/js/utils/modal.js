
arcs.utils.modal = function(options) {
  var $modal, defaults, id, _i, _len, _ref,
    _this = this;
  defaults = {
    template: '',
    templateValues: {},
    draggable: false,
    handle: null,
    backdrop: true,
    "class": null,
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
  if (!$('#modal').length) $('body').append(arcs.templates.modalWrapper);
  $modal = $('#modal');
  $modal.modal({
    backdrop: options.backdrop
  });
  $modal.html(Mustache.render(options.template, options.templateValues));
  if (options["class"] != null) $modal.addClass(options["class"]);
  $modal.modal('show');
  if (options.draggable) {
    $modal.draggable({
      handle: options.handle
    });
  }
  _ref = _.keys(options.buttons);
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    id = _ref[_i];
    $modal.find("#" + id).one('click', function(e) {
      var button, callback, closeAfter, context, id_, vals, _j, _len2, _ref2, _ref3, _ref4;
      vals = {};
      if (options.inputs.length) {
        _ref2 = options.inputs;
        for (_j = 0, _len2 = _ref2.length; _j < _len2; _j++) {
          id_ = _ref2[_j];
          vals[id_] = $modal.find("#" + id_).val();
        }
      }
      button = options.buttons[e.target.id];
      if (_.isFunction(button)) {
        callback = button;
        closeAfter = true;
        context = null;
      } else {
        context = button.context;
        callback = (_ref3 = button.callback) != null ? _ref3 : function() {};
        closeAfter = (_ref4 = button.closeAfter) != null ? _ref4 : true;
      }
      if (button.context != null) callback = _.bind(callback, context);
      callback(vals, $modal);
      if (closeAfter) return $modal.modal('hide');
    });
  }
  return $modal;
};
