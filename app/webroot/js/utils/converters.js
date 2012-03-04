(function() {

  arcs.utils.convertBytes = function(bytes) {
    var sizes;
    bytes = parseInt(bytes, 10);
    if (!isFinite(bytes)) return 'unknown size';
    sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
    while (!(bytes < 1024)) {
      bytes /= 1024;
      sizes.shift();
    }
    return Math.round(bytes) + sizes.shift();
  };

}).call(this);
