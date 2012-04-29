# annotation.coffee
# -----------------
class arcs.models.Annotation extends Backbone.Model

  url: ->
    return arcs.baseURL + 'annotations' if @isNew()
    arcs.baseURL + "annotations/#{@id}"

  setScaled: (box, height, width) ->
    @set
      x1: box.x1 / width
      y1: box.y1 / height
      x2: box.x2 / width
      y2: box.y2 / height

  scaleTo: (height, width) ->
    x1: @get('x1') * width
    y1: @get('y1') * height
    x2: @get('x2') * width
    y2: @get('y2') * height

  getType: ->
    return 'Relation' if @get 'relation'
    return 'Transcription' if @get 'transcript'
    'URL' if @get 'url'

  parse: (response) ->
    response = response.Annotation if response.Annotation?
    response
