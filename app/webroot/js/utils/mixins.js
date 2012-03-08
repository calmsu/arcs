(function() {

  _.mixin({
    isNumeric: function(val) {
      return !isNaN(parseFloat(val)) && isFinite(val);
    }
  });

}).call(this);
