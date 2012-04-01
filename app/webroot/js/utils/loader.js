(function() {

  arcs.loader = arcs.utils.loader = {
    show: function() {
      if (!$('#arcs-loader').length) $('body').append(arcs.tmpl('ui/loader'));
      return $('#arcs-loader').show();
    },
    hide: function() {
      return $('#arcs-loader').hide();
    }
  };

}).call(this);
