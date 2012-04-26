(function() {
  var _sync;

  if (document.location.href.match(/:8080\/~[a-z0-9]+\//)) {
    arcs.baseURL += document.location.href.match(/~[a-z0-9]+\//);
    arcs.baseURL += 'arcs/';
  }

  if (arcs.mode > 0) {
    arcs.dev = {};
    arcs.dev.reload = false;
    arcs.dev.reloadAt = 2000;
    _sync = Backbone.sync;
    Backbone.sync = function(method, model, options) {
      arcs.log('Backbone.sync:', method, model, options);
      return _sync(method, model, options);
    };
    arcs.dev.reloadStylesheets = function() {
      var query;
      query = '?reload=' + new Date().getTime();
      return $('link[rel="stylesheet"]').each(function() {
        return this.href = this.href.replace(/\?.*|$/, query);
      });
    };
    if (arcs.dev.reload) {
      setInterval(arcs.dev.reloadStylesheets, arcs.dev.reloadAt);
    }
  }

}).call(this);
