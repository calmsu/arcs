# hotspot.coffee
# --------------
class arcs.models.Hotspot extends Backbone.Model
  urlRoot: arcs.baseURL + 'hotspots'

  parse: (response) ->
    response = response.Hotspot if response.Hotspot?
    response
