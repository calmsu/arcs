
arcs.utils.notify = function(msg, type, hide) {
  var $el, duration;
  if (type == null) type = 'info';
  if (hide == null) hide = 3;
  if (!$('#notification').length) $('body').append(arcs.tmpl('notification'));
  $el = $('#notification');
  $el.find('#msg').html(msg);
  if (type !== 'info' && type !== 'error' && type !== 'success') type = 'info';
  $el.removeClass('alert-info alert-error alert-success');
  $el.addClass("alert-" + type);
  $el.show();
  if (hide) {
    duration = _.isNumber(hide) ? hide : 3;
    setTimeout((function() {
      return $el.fadeOut(500);
    }), duration * 1000);
  }
  return $el;
};
