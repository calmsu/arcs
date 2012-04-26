preloaded = []
arcs.preload = (images) ->
  images = [images] if typeof images == 'string'
  for img in images
    continue unless img not in preloaded
    $('<img />').attr('src', img).hide().appendTo('body')
    preloaded.push img
