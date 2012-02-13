
arcs.utils.hash = {
  set: function(val) {
    this.history.push(document.location.hash.slice(1));
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
