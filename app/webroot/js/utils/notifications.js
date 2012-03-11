(function() {

  arcs.notify = function(msg, type, hide) {
    var $el, duration, types;
    if (type == null) type = 'info';
    if (hide == null) hide = 3;
    types = {
      info: 'Heads Up!',
      error: 'Error',
      success: 'Success!'
    };
    if (!$('#notification').length) $('body').append(arcs.tmpl('ui/notification'));
    $el = $('#notification');
    if (!(type in types)) type = 'info';
    $el.removeClass('alert-info alert-error alert-success');
    $el.addClass("alert-" + type);
    $el.find('#header').html(types[type]);
    $el.find('#msg').html(msg);
    $el.show();
    if (hide) {
      duration = _.isNumber(hide) ? hide : 3;
      setTimeout((function() {
        return $el.fadeOut(500);
      }), duration * 1000);
    }
    return $el;
  };

}).call(this);
