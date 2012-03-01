
arcs.utils.convertBytes = function(bytes) {
  var sizes;
  if (!_.isNumber(bytes)) return 'unknown size';
  sizes = ['B', 'KB', 'MB', 'GB', 'TB'];
  while (!(bytes < 1024)) {
    arcs.log(bytes);
    bytes /= 1024;
    sizes.shift();
  }
  return Math.round(bytes) + sizes.shift();
};
