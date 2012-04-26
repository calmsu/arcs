(function() {

  arcs.utils.hash = {
    set: function(val, uri_encode) {
      if (uri_encode == null) uri_encode = false;
      this.history.push(document.location.hash);
      if (uri_encode) val = encodeURIComponent(val);
      return document.location.hash = val;
    },
    get: function(uri_decode) {
      var hash;
      if (uri_decode == null) uri_decode = false;
      hash = document.location.hash.slice(1);
      if (uri_decode) return decodeURIComponent(hash);
      return hash;
    },
    rewind: function() {
      return this.set(this.history.pop());
    },
    history: []
  };

  arcs.utils.params = {
    get: function(name) {
      var p, pair, params, _i, _len;
      params = document.location.search.slice(1).split('&');
      for (_i = 0, _len = params.length; _i < _len; _i++) {
        p = params[_i];
        pair = p.split('=');
        if (pair[0] === name) return decodeURIComponent(unescape(pair[1]));
      }
    }
  };

}).call(this);
