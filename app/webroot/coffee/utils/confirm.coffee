arcs.confirm = (msg..., onConfirm) ->
  new arcs.views.Modal
    title: msg[0]
    subtitle: msg[1] ? ''
    buttons:
      yes: 
        class: 'btn btn-danger'
        callback: onConfirm
      no: ->
