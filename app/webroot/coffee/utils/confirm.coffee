arcs.confirm = (msg..., onConfirm) ->
  new arcs.views.Modal
    title: msg[0]
    subtitle: msg[1] ? ''
    buttons:
      yes: 
        class: 'btn btn-danger'
        callback: onConfirm
      no: ->

arcs.prompt = (msg...) ->
  new arcs.views.Modal
    title: msg[0]
    subtitle: msg[1] ? ''
    buttons:
      ok: 
        class: 'btn btn-primary'
        callback: ->

arcs.needsLogin = ->
  # Called in response to 401's
  if arcs.user.get 'loggedIn'
    title = 'You have been logged out.'
  else
    title = "You'll need to login to do that."
  new arcs.views.Modal
    title: title
    subtitle: "Click 'Login' below to go to the login page."
    buttons:
      login: -> location.href = arcs.url 'login'
      cancel: ->
