(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.User = (function(_super) {

    __extends(User, _super);

    function User() {
      User.__super__.constructor.apply(this, arguments);
    }

    User.prototype.events = {
      'click #edit-btn': 'editAccount'
    };

    User.prototype.editAccount = function() {
      return new arcs.views.Modal({
        title: 'Edit Your Account',
        subtitle: "If you'd like your password to stay the same, leave the " + "password fields blank.",
        inputs: {
          name: {
            value: this.model.get('name')
          },
          username: {
            value: this.model.get('username')
          },
          email: {
            value: this.model.get('email')
          },
          password: {
            type: 'password',
            label: 'Old Password'
          },
          new_password: {
            type: 'password',
            label: 'New Password'
          },
          new_password_confirm: {
            type: 'password',
            label: 'Confirm New Password'
          }
        },
        buttons: {
          save: {
            validate: true,
            "class": 'btn success',
            callback: function() {
              return arcs.notify('saved', 'success');
            }
          },
          cancel: function() {}
        }
      });
    };

    return User;

  })(Backbone.View);

}).call(this);
