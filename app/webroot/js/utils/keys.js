var _this = this;

arcs.utils.keys = {
  initialize: function() {
    return $(document).bind('keydown', this.delegate);
  },
  delegate: function(e) {
    var bubble, callback, m, mappings, modifier, _i, _len;
    if (e.target.type === 'text' || /textarea|select/i.test(e.target.nodeName)) {
      return true;
    }
    if (e.ctrlKey || e.shiftKey || e.altKey || e.metaKey) {
      modifier = true;
    } else {
      modifier = false;
    }
    mappings = arcs.utils.keys.get(e.which, modifier);
    if (!mappings.length) return true;
    bubble = false;
    for (_i = 0, _len = mappings.length; _i < _len; _i++) {
      m = mappings[_i];
      if (m.bubble) bubble = true;
      if (m.context) {
        callback = _.bind(m.callback, m.context);
      } else {
        callback = m.callback;
      }
      callback(e);
    }
    if (!bubble) {
      e.preventDefault();
      return false;
    }
    return true;
  },
  add: function(key, modifier, callback, context, bubble) {
    if (context == null) context = null;
    if (bubble == null) bubble = false;
    this.mappings.push({
      key: key,
      modifier: modifier,
      callback: callback,
      context: context,
      bubble: bubble
    });
    return this.mappings;
  },
  get: function(keyCode, modifier) {
    var matches,
      _this = this;
    if (modifier == null) modifier = false;
    return matches = _.filter(this.mappings, function(map) {
      return map.key === _this.humanize(keyCode) && map.modifier === modifier;
    });
  },
  mappings: [],
  humanize: function(keyCode) {
    if (_.has(this.specialKeys, keyCode)) return this.specialKeys[keyCode];
    return String.fromCharCode(keyCode).toLowerCase();
  },
  specialKeys: {
    8: "backspace",
    9: "tab",
    13: "return",
    16: "shift",
    17: "ctrl",
    18: "alt",
    27: "esc",
    32: "space",
    37: "left",
    38: "up",
    39: "right",
    40: "down",
    107: "+",
    109: "-"
  }
};

arcs.utils.keys.initialize();
