(function() {

  arcs.notify = function(msg, type, hide) {
    var $el, duration;
    if (type == null) type = 'info';
    if (hide == null) hide = 3;
    if (!$('#notification').length) $('body').append(arcs.tmpl('ui/notification'));
    $el = $('#notification');
    $el.removeClass(function(i, klass) {
      return (klass.match(/\balert-\S+/g) || []).join(' ');
    });
    $el.addClass("alert-" + type);
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
