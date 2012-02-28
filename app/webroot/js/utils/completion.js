
arcs.utils.complete = {
  get: function(url) {
    var result;
    result = [];
    $.ajax({
      url: arcs.baseURL + url,
      async: false,
      dataType: 'json',
      success: function(data) {
        return result = _.without(_.uniq(_.values(data)), null);
      }
    });
    return result;
  },
  date: function(url) {
    var aliases, d, dates, fmt, parse_fmt, raw_dates;
    raw_dates = this.get(url);
    fmt = 'MM-DD-YYYY';
    parse_fmt = 'YYYY-MM-DD HH:mm:ss';
    dates = (function() {
      var _i, _len, _results;
      _results = [];
      for (_i = 0, _len = raw_dates.length; _i < _len; _i++) {
        d = raw_dates[_i];
        _results.push(moment(d, parse_fmt).format(fmt));
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
    return _.uniq(_.union(dates, aliases));
  },
  user: function() {
    return this.get('users/complete');
  },
  tag: function() {
    return this.get('tags/complete');
  },
  title: function() {
    return this.get('resources/complete/title');
  },
  type: function() {
    return this.get('resources/complete/type');
  },
  created: function() {
    return this.date('resources/complete/created');
  },
  modified: function() {
    return this.date('resources/complete/modified');
  }
};

_.bindAll(arcs.utils.complete);

arcs.utils.autocomplete = function(opts) {
  var $el, addTerm, defaults, focus, getLast, options, select, split;
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
