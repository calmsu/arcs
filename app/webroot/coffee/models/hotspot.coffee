# hotspot.coffee
# --------------
# Hotspot model
class arcs.models.Hotspot extends Backbone.Model
  urlRoot: arcs.baseURL + 'hotspots'

  parse: (response) ->
    response = response.Hotspot if response.Hotspot?
    response
