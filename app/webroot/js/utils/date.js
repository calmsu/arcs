(function() {
  var formats;

  formats = {
    second: [1, 60],
    minute: [60, 3600],
    hour: [3600, 604800],
    week: [604800, 2419200],
    month: [2419200, 31536000],
    year: [31536000, Number.MAX_VALUE]
  };

  arcs.relativeDate = function(date) {
    var delta, formatted, name, ratio, threshold, vals;
    if (!(date instanceof Date)) date = new Date(date);
    delta = (new Date).getTime() - date.getTime();
    for (name in formats) {
      vals = formats[name];
      threshold = vals[0], ratio = vals[1];
      if (delta < threshold) {
        formatted = Math.round(delta / ratio);
        return "" + formatted + " " + (arcs.inflector.pluralize(name, formatted)) + " ago";
      }
    }
  };

}).call(this);
