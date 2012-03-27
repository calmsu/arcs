(function() {
  var preloaded,
    __indexOf = Array.prototype.indexOf || function(item) { for (var i = 0, l = this.length; i < l; i++) { if (i in this && this[i] === item) return i; } return -1; };

  preloaded = [];

  arcs.preload = function(images) {
    var img, _i, _len, _results;
    if (typeof images === 'string') images = [images];
    _results = [];
    for (_i = 0, _len = images.length; _i < _len; _i++) {
      img = images[_i];
      if (__indexOf.call(preloaded, img) >= 0) continue;
      $('<img />').attr('src', img).hide().appendTo('body');
      _results.push(preloaded.push(img));
    }
    return _results;
  };

}).call(this);
