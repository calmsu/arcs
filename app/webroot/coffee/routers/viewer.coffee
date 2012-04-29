# viewer.coffee
# ---------------
class arcs.routers.Viewer extends Backbone.Router

  routes:
    ':id'       : 'noIndex'
    ':id/'      : 'noIndex'
    ':id/:index': 'indexChange'

  noIndex: (id) ->
    arcs.bus.trigger 'indexChange', 0
      noNavigate: true
      replace: true

  indexChange: (id, index) ->
    index -= 1 if _.isNumeric(index)
    arcs.bus.trigger 'indexChange', index,
      noNavigate: true
