(function() {
  var __slice = Array.prototype.slice;

  window.arcs = {};

  arcs.views = {};

  arcs.models = {};

  arcs.collections = {};

  arcs.utils = {};

  arcs.routers = {};

  arcs.templates = {};

  arcs.mode = CAKE_DEBUG;

  arcs.debug = arcs.mode > 0;

  arcs.version = "0.9.1";

  arcs.baseURL = '/';

  arcs.log = function() {
    var msg;
    msg = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
    if (arcs.debug && ((typeof console !== "undefined" && console !== null ? console.log : void 0) != null)) {
      return console.log.apply(console, ['[ARCS]:'].concat(__slice.call(msg)));
    }
  };

  arcs.tmpl = function(key, data, func) {
    var tmpl;
    if (func == null) func = _.template;
    tmpl = _.has(JST, key) ? JST[key] : key;
    return func(tmpl, data != null ? data : {});
  };

  _.extend(arcs, Backbone.Events);

}).call(this);
