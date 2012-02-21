
arcs.utils.loader = {
  show: function() {
    if (!$('#arcs-loader').length) $('body').append(arcs.templates.loader);
    return $('#arcs-loader').show();
  },
  hide: function() {
    return $('#arcs-loader').hide();
  }
};
