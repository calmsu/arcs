(function() {
  var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  arcs.utils.Keys = (function() {

    function Keys(sel) {
      if (sel == null) sel = document;
      this.delegate = __bind(this.delegate, this);
      $(sel).on('keydown', this.delegate);
    }

    Keys.prototype.delegate = function(e) {
      var bubble, callback, m, mappings, modifier, _i, _len;
      if (e.target.type === 'text' || /textarea|select/i.test(e.target.nodeName)) {
        return true;
      }
      if (e.ctrlKey || e.shiftKey || e.altKey || e.metaKey) {
        modifier = true;
      } else {
        modifier = false;
      }
      mappings = this.get(e.which, modifier);
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
    };

    Keys.prototype.add = function(key, callback, context, bubble) {
      if (bubble == null) bubble = false;
      return this.mappings.push({
        key: key,
        callback: callback,
        context: context,
        bubble: bubble
      });
    };

    Keys.prototype.map = function(ctx, map) {
      var cb, key, _results;
      _results = [];
      for (key in map) {
        cb = map[key];
        _results.push(this.mappings.push({
          key: key,
          callback: cb,
          context: ctx
        }));
      }
      return _results;
    };

    Keys.prototype.get = function(keyCode, modifier) {
      var key, matches,
        _this = this;
      if (modifier == null) modifier = false;
      key = this.humanize(keyCode, modifier);
      return matches = _.filter(this.mappings, function(map) {
        return map.key === key;
      });
    };

    Keys.prototype.mappings = [];

    Keys.prototype.humanize = function(keyCode, modifier) {
      var key;
      key = modifier ? 'ctrl+' : '';
      if (this.specialKeys[keyCode] != null) {
        return key += this.specialKeys[keyCode];
      }
      return key += String.fromCharCode(keyCode).toLowerCase();
    };

    Keys.prototype.specialKeys = {
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
      187: "+",
      189: "-",
      191: "?"
    };

    return Keys;

  })();

  arcs.keys = arcs.utils.keys = new arcs.utils.Keys;

}).call(this);
