(function() {
  var __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  arcs.views.Profile = (function(_super) {

    __extends(Profile, _super);

    function Profile() {
      Profile.__super__.constructor.apply(this, arguments);
    }

    Profile.prototype.events = {
      'click #edit-btn': 'editAccount'
    };

    Profile.prototype.editAccount = function() {
      var _this = this;
      return new arcs.views.Modal({
        title: 'Edit Your Account',
        subtitle: "If you'd like your password to stay the same, leave the " + "password field blank.",
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
            type: 'password'
          }
        },
        buttons: {
          save: {
            validate: true,
            "class": 'btn btn-success',
            callback: function(vals) {
              if (vals.password === '') delete vals.password;
              arcs.loader.show();
              return _this.model.save(vals, {
                success: arcs.loader.hide
              });
            }
          },
          cancel: function() {}
        }
      });
    };

    return Profile;

  })(Backbone.View);

}).call(this);
