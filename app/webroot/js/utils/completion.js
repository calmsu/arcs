
arcs.utils.complete = {
  "default": function(url) {
    var result;
    result = [];
    $.ajax({
      url: arcs.baseURL + url,
      async: false,
      dataType: 'json',
      success: function(data) {
        arcs.log(data);
        return result = _.without(_.uniq(_.values(data)), null);
      }
    });
    return result;
  },
  users: function() {
    return arcs.utils.complete["default"]('users/complete');
  },
  tags: function() {
    return arcs.utils.complete["default"]('tags/complete');
  },
  titles: function() {
    return arcs.utils.complete["default"]('resources/complete/title');
  },
  types: function() {
    return arcs.utils.complete["default"]('resources/complete/type');
  }
};

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
