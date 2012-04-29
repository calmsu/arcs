(function() {
  var __slice = Array.prototype.slice;

  window.arcs = {};

  arcs.views = {};

  arcs.models = {};

  arcs.collections = {};

  arcs.utils = {};

  arcs.routers = {};

  arcs.templates = {};

  arcs.bus = {};

  _.extend(arcs.bus, Backbone.Events);

  arcs.mode = CAKE_DEBUG;

  arcs.debug = arcs.mode > 0;

  arcs.version = "0.9.6";

  arcs.baseURL = '/';

  arcs.url = function() {
    var components;
    components = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
    return arcs.baseURL + components.join('/');
  };

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

  $.fn.extend({
    toggleAttr: function(attr) {
      if ($(this).attr(attr)) return $(this).removeAttr(attr);
      return $(this).attr(attr, attr);
    }
  });

  $.postJSON = function(url, data, success) {
    return $.ajax({
      url: url,
      data: JSON.stringify(data),
      type: 'POST',
      contentType: 'application/json',
      dataType: 'json',
      success: success
    });
  };

}).call(this);
