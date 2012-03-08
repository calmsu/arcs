# notifications.coffee
# --------------------

# Display a notification message.
#
# msg  - string mesage
# type - one of 'info', 'error', 'success', defaults to 'info'
# hide - if number, hide after this many seconds. If truthy non-number,
#        or not given, hide after 3 seconds. If falsey, don't hide.
arcs.notify = (msg, type='info', hide=3) ->

  # Create a notification div if there isn't already one.
  unless $('#notification').length
    $('body').append arcs.tmpl 'ui/notification'
  $el = $('#notification')

  # Set the content.
  $el.find('#msg').html(msg)

  # Set the type.
  type = 'info' unless type in ['info', 'error', 'success']
  $el.removeClass 'alert-info alert-error alert-success'
  $el.addClass "alert-#{type}"

  # Make it visible.
  $el.show()
  
  # Fade out 
  if hide
    duration = if _.isNumber hide then hide else 3
    setTimeout (-> $el.fadeOut 500), duration * 1000
  $el
