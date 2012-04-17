(function() {

  _.mixin({
    isNumeric: function(val) {
      return !isNaN(parseFloat(val)) && isFinite(val);
    },
    inverse: function(object) {
      var k, result, v;
      result = {};
      for (k in object) {
        v = object[k];
        result[v] = k;
      }
      return result;
    }
  });

}).call(this);
