# loader.coffee
# -------------
arcs.loader = arcs.utils.loader = 
  show: ->
    unless $('.loading').length
      $('body').append arcs.tmpl 'ui/loader'
    $('.loading').show()
  hide: ->
    $('.loading').hide()
