
arcs.utils.hash = {
  set: function(val) {
    this.history.push(document.location.hash);
    return document.location.hash = val;
  },
  get: function() {
    return document.location.hash.slice(1);
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
