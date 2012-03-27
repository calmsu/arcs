class arcs.views.User extends Backbone.View

  events:
    'click #edit-btn': 'editAccount'

  editAccount: ->
    new arcs.views.Modal
      title: 'Edit Your Account'
      subtitle: "If you'd like your password to stay the same, leave the " +
        "password fields blank."
      inputs:
        name:
          value: @model.get 'name'
        username:
          value: @model.get 'username'
        email:
          value: @model.get 'email'
        password:
          type: 'password'
          label: 'Old Password'
        new_password:
          type: 'password'
          label: 'New Password'
        new_password_confirm:
          type: 'password'
          label: 'Confirm New Password'

      buttons:
        save:
          validate: true
          class: 'btn success'
          callback: ->
            arcs.notify 'saved', 'success'
        cancel: ->
