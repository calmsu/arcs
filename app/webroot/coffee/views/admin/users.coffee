# users.coffee
# ------------
# Manage user accounts.
arcs.views.admin ?= {}
class arcs.views.admin.Users extends Backbone.View

  USER_ROLES:
    'Researcher': 2
    'Sr. Researcher': 1
    'Admin': 0

  initialize: ->
    @collection.on 'add remove change sync', @render, @
    @render()

  events:
    'click #delete-btn': 'deleteUser'
    'click #edit-btn'  : 'editUser'
    'click #new-btn'   : 'newUser'
    'click #invite-btn': 'sendInvite'

  deleteUser: (e) ->
    user = @collection.get $(e.currentTarget).data('id')
    arcs.confirm "Are you sure you want to delete this user?", 
      "The account for <b>#{user.get('name')}</b> will be deleted.", =>
        arcs.loader.show()
        user.destroy 
          success: arcs.loader.hide

  editUser: (e) ->
    user = @collection.get $(e.currentTarget).data('id')
    new arcs.views.Modal
      title: 'Edit user'
      inputs:
        name:
          value: user.get 'name'
        username:
          value: user.get 'username'
        email:
          value: user.get 'email'
        role:
          type: 'select'
          options: @USER_ROLES
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (vals) =>
            arcs.loader.show()
            user.unset 'password'
            user.save vals,
              success: arcs.loader.hide
        cancel: ->
    $('#modal-role-input').val user.get('role')

  newUser: ->
    new arcs.views.Modal
      title: 'Create a new user'
      inputs:
        name:
          focused: true
        username: true 
        email: true
        password: 
          type: 'password'
        role:
          type: 'select'
          options: @USER_ROLES
      buttons:
        save:
          class: 'btn btn-success'
          callback: (vals) =>
            user = new arcs.models.User(vals)
            arcs.loader.show()
            user.save {},
              success: (m, r) =>
                user.id = r.id
                arcs.loader.hide()
                @collection.add user
        cancel: ->

  sendInvite: ->
    new arcs.views.Modal
      title: 'Invite someone to ARCS'
      subtitle: "Provide an email address and we'll send them a link that will " +
        "allow them to create an account."
      inputs:
        email: 
          focused: true
        role:
          type: 'select'
          options: @USER_ROLES
      buttons:
        send:
          class: 'btn btn-success'
          callback: (vals) =>
            vals.email = $.trim vals.email
            $.postJSON arcs.baseURL + 'users/invite', vals, =>
              @collection.add vals
        cancel: ->

  render: ->
    @$('#users').html arcs.tmpl 'admin/users', 
      users: @collection.toJSON()
    @
