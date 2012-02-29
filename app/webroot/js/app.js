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

arcs.version = "0.9.0";

arcs.baseURL = '/';

arcs.log = function() {
  var msg;
  msg = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
  if (arcs.debug && ((typeof console !== "undefined" && console !== null ? console.log : void 0) != null)) {
    return console.log.apply(console, ['[ARCS]:'].concat(__slice.call(msg)));
  }
};

arcs.tmpl = function(key, data) {
  return Mustache.render(arcs.templates[key], data != null ? data : {});
};

_.extend(arcs, Backbone.Events);
