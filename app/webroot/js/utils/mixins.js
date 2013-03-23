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
    },
    twins: function(array) {
      return _.uniq(array).length === 1;
    },
    surrounding: function(array, index, len) {
      var after, alt, before, result;
      before = _.first(array, index);
      after = _.rest(array, index + 1);
      result = [array[index]];
      alt = true;
      while (len > result.length && (before.length || after.length)) {
        if (alt && before.length) {
          result.unshift(before.pop());
        } else if (after.length) {
          result.push(after.shift());
        }
        alt = !alt;
      }
      return result;
    }
  });

}).call(this);
