# loader.coffee
# -------------
arcs.loader = arcs.utils.loader = 
  show: ->
    unless $('#arcs-loader').length
      $('body').append arcs.tmpl 'ui/loader'
    $('#arcs-loader').show()
  hide: ->
    $('#arcs-loader').hide()
