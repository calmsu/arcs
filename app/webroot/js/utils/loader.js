(function() {

  arcs.loader = arcs.utils.loader = {
    show: function() {
      if (!$('.loading').length) $('body').append(arcs.tmpl('ui/loader'));
      return $('.loading').show();
    },
    hide: function() {
      return $('.loading').hide();
    }
  };

}).call(this);
