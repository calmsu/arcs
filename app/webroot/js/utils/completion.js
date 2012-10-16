(function() {
  var complete, completeFacet, memoize;

  memoize = function(f, expire) {
    var cache;
    cache = {};
    return function() {
      var args, key, res, time;
      args = _.toArray(arguments);
      key = args.join('');
      time = Date.now();
      if (key in cache && (time - cache[key][0] < expire)) return cache[key][1];
      res = f.apply(this, args);
      cache[key] = [time, res];
      return res;
    };
  };

  completeFacet = function(category, query) {
    var result;
    if (query == null) query = '';
    result = [];
    $.ajax({
      url: arcs.baseURL + ("resources/complete?cat=" + category + "&q=" + query),
      async: false,
      dataType: 'json',
      success: function(data) {
        return result = _.compact(_.uniq(_.values(data)));
      }
    });
    return result;
  };

  complete = function(url) {
    var result;
    result = [];
    $.ajax({
      url: arcs.baseURL + url,
      async: false,
      dataType: 'json',
      success: function(data) {
        return result = _.compact(_.uniq(_.values(data)));
      }
    });
    return result;
  };

  arcs.completeFacet = memoize(completeFacet, 30000);

  arcs.complete = memoize(complete, 30000);

  arcs.completeDate = function(url) {
    var aliases, d, dates, fmt, parseFmt, raw, _ref;
    raw = arcs.complete(url);
    _ref = ['DD-MM-YYYY', 'YYYY-MM-DD HH:mm:ss'], fmt = _ref[0], parseFmt = _ref[1];
    dates = (function() {
      var _i, _len, _results;
      _results = [];
      for (_i = 0, _len = raw.length; _i < _len; _i++) {
        d = raw[_i];
        _results.push(moment(d, parseFmt).format(fmt));
      }
      return _results;
    })();
    aliases = [
      {
        label: 'today',
        value: moment().format(fmt)
      }, {
        label: 'yesterday',
        value: moment().subtract('days', 1).format(fmt)
      }
    ];
    return _.union(dates, aliases);
  };

  arcs.utils.autocomplete = function(opts) {
    var $el, addTerm, defaults, focus, getLast, options, select, split, _ref;
    defaults = {
      source: [],
      multiple: false,
      sel: null
    };
    options = _.extend(defaults, opts);
    $el = $(options.sel);
    split = function(val) {
      return val.split(/,\s*/);
    };
    getLast = function(term) {
      return split(term).pop();
    };
    addTerm = function(val, appendage) {
      var terms;
      terms = split(val);
      terms.pop();
      terms.push(appendage, '');
      return terms.join(', ');
    };
    if (_.isFunction(options.source) && options.source.length === 0) {
      _ref = [options.source(), options.source], options.source = _ref[0], options._source = _ref[1];
    }
    if (options.multiple) {
      options._source = options.source;
      options.source = function(request, response) {
        var filter;
        filter = $.ui.autocomplete.filter;
        return response(filter(options._source, getLast(request.term)));
      };
      select = function(event, ui) {
        this.value = addTerm(this.value, ui.item.value);
        return false;
      };
      focus = function() {
        return false;
      };
    }
    $el.autocomplete({
      source: options.source,
      autoFocus: true,
      focus: focus != null ? focus : function() {},
      minLength: 0,
      select: select != null ? select : function() {}
    });
    return $el.on('keydown', function(e) {
      if (e.keyCode === 9) {
        e.preventDefault();
        return false;
      }
    });
  };

}).call(this);
