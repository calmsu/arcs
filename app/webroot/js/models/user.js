(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.models.User = (function(_super) {

    __extends(User, _super);

    function User() {
      User.__super__.constructor.apply(this, arguments);
    }

    User.prototype.ROLES = {
      'Admin': 0,
      'Sr. Researcher': 1,
      'Researcher': 2,
      'Guest': 3
    };

    User.prototype.urlRoot = arcs.baseURL + 'users';

    User.prototype.is = function(role) {
      return this.get('role') <= this.ROLES[role];
    };

    User.prototype.isLoggedIn = function() {
      return this.id != null;
    };

    User.prototype.isAdmin = function() {
      return this.get('role') === this.ROLES['Admin'];
    };

    return User;

  })(Backbone.Model);

}).call(this);
