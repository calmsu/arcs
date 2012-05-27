(function() {
  var _base,
    __hasProp = Object.prototype.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor; child.__super__ = parent.prototype; return child; };

  if ((_base = arcs.views).admin == null) _base.admin = {};

  arcs.views.admin.Users = (function(_super) {

    __extends(Users, _super);

    function Users() {
      Users.__super__.constructor.apply(this, arguments);
    }

    Users.prototype.USER_ROLES = {
      'Researcher': 2,
      'Sr. Researcher': 1,
      'Admin': 0
    };

    Users.prototype.initialize = function() {
      this.collection.on('add remove change sync', this.render, this);
      return this.render();
    };

    Users.prototype.events = {
      'click #delete-btn': 'deleteUser',
      'click #edit-btn': 'editUser',
      'click #new-btn': 'newUser',
      'click #invite-btn': 'sendInvite'
    };

    Users.prototype.deleteUser = function(e) {
      var user,
        _this = this;
      user = this.collection.get($(e.currentTarget).data('id'));
      return arcs.confirm("Are you sure you want to delete this user?", "The account for <b>" + (user.get('name')) + "</b> will be deleted.", function() {
        arcs.loader.show();
        return user.destroy({
          success: arcs.loader.hide
        });
      });
    };

    Users.prototype.editUser = function(e) {
      var user,
        _this = this;
      user = this.collection.get($(e.currentTarget).data('id'));
      new arcs.views.Modal({
        title: 'Edit user',
        inputs: {
          name: {
            value: user.get('name')
          },
          username: {
            value: user.get('username')
          },
          email: {
            value: user.get('email')
          },
          role: {
            type: 'select',
            options: this.USER_ROLES
          }
        },
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function(vals) {
              arcs.loader.show();
              user.unset('password');
              return user.save(vals, {
                success: arcs.loader.hide
              });
            }
          },
          cancel: function() {}
        }
      });
      return $('#modal-role-input').val(user.get('role'));
    };

    Users.prototype.newUser = function() {
      var _this = this;
      return new arcs.views.Modal({
        title: 'Create a new user',
        inputs: {
          name: {
            focused: true
          },
          username: true,
          email: true,
          password: {
            type: 'password'
          },
          role: {
            type: 'select',
            options: this.USER_ROLES
          }
        },
        buttons: {
          save: {
            "class": 'btn btn-success',
            callback: function(vals) {
              var user;
              user = new arcs.models.User(vals);
              arcs.loader.show();
              return user.save({}, {
                success: function(m, r) {
                  user.id = r.id;
                  arcs.loader.hide();
                  return _this.collection.add(user);
                }
              });
            }
          },
          cancel: function() {}
        }
      });
    };

    Users.prototype.sendInvite = function() {
      var _this = this;
      return new arcs.views.Modal({
        title: 'Invite someone to ARCS',
        subtitle: "Provide an email address and we'll send them a link that will " + "allow them to create an account.",
        inputs: {
          email: {
            focused: true
          },
          role: {
            type: 'select',
            options: this.USER_ROLES
          }
        },
        buttons: {
          send: {
            "class": 'btn btn-success',
            callback: function(vals) {
              vals.email = $.trim(vals.email);
              return $.postJSON(arcs.baseURL + 'users/invite', vals, function() {
                return _this.collection.add(vals);
              });
            }
          },
          cancel: function() {}
        }
      });
    };

    Users.prototype.render = function() {
      this.$('#users').html(arcs.tmpl('admin/users', {
        users: this.collection.toJSON()
      }));
      return this;
    };

    return Users;

  })(Backbone.View);

}).call(this);
