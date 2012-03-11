# notifications.coffee
# --------------------

# Display a notification message.
#
# msg  - string mesage
# type - one of 'info', 'error', 'success', defaults to 'info'
# hide - if number, hide after this many seconds. If truthy non-number,
#        or not given, hide after 3 seconds. If falsey, don't hide.
arcs.notify = (msg, type='info', hide=3) ->

  types =
    info    : 'Heads Up!'
    error   : 'Error'
    success : 'Success!'

  # Create a notification div if there isn't already one.
  unless $('#notification').length
    $('body').append arcs.tmpl 'ui/notification'
  $el = $('#notification')

  # Set the type.
  type = 'info' unless type of types
  $el.removeClass 'alert-info alert-error alert-success'
  $el.addClass "alert-#{type}"

  # Set the header.
  $el.find('#header').html types[type]

  # Set the content.
  $el.find('#msg').html msg

  # Make it visible.
  $el.show()
  
  # Fade out 
  if hide
    duration = if _.isNumber hide then hide else 3
    setTimeout (-> $el.fadeOut 500), duration * 1000
  $el
