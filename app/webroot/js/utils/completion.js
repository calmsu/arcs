
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
    return arcs.utils.complete["default"]('resources/complete');
  }
};

arcs.utils.autocomplete = function(opts) {
  var $el;
  $el = $(opts.sel);
  $el.autocomplete({
    source: opts.source,
    autoFocus: true
  });
  return $el.on('autocompleteselect', function(event, ui) {
    $el.val(ui.item.value);
    return false;
  });
};
