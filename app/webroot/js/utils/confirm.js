(function() {
  var __slice = Array.prototype.slice;

  arcs.confirm = function() {
    var msg, onConfirm, _i, _ref;
    msg = 2 <= arguments.length ? __slice.call(arguments, 0, _i = arguments.length - 1) : (_i = 0, []), onConfirm = arguments[_i++];
    return new arcs.views.Modal({
      title: msg[0],
      subtitle: (_ref = msg[1]) != null ? _ref : '',
      buttons: {
        yes: {
          "class": 'btn btn-danger',
          callback: onConfirm
        },
        no: function() {}
      }
    });
  };

  arcs.prompt = function() {
    var msg, _ref;
    msg = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
    return new arcs.views.Modal({
      title: msg[0],
      subtitle: (_ref = msg[1]) != null ? _ref : '',
      buttons: {
        ok: {
          "class": 'btn btn-primary',
          callback: function() {}
        }
      }
    });
  };

  arcs.needsLogin = function() {
    var title;
    if (arcs.user.get('loggedIn')) {
      title = 'You have been logged out.';
    } else {
      title = "You'll need to login to do that.";
    }
    return new arcs.views.Modal({
      title: title,
      subtitle: "Click 'Login' below to go to the login page.",
      buttons: {
        login: function() {
          return location.href = arcs.url('login');
        },
        cancel: function() {}
      }
    });
  };

}).call(this);
