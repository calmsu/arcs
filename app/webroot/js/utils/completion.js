
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
