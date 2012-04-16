(function() {

  $(function() {
    var warning;
    warning = function() {
      return arcs.prompt("Your browser is old and unsupported.", "<p>ARCS was designed for modern web browsers. Please upgrade to the " + "newest version of <a href='http://google.com/chrome'>Chrome</a> or " + "<a href='http://mozilla.org/en-US/firefox/new'>Firefox</a>.</p>If " + "you'd like to give it a try anyway, click 'Ok' below.");
    };
    if (!(jQuery.support.ajax && jQuery.support.cors && history.pushState)) {
      return warning();
    }
  });

}).call(this);
