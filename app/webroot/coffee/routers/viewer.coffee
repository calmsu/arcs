# viewer.coffee
# ---------------
class arcs.routers.Viewer extends Backbone.Router

  routes:
    ':id'       : 'noIndex'
    ':id/'      : 'noIndex'
    ':id/:index': 'indexChange'

  noIndex: (id) ->
    arcs.trigger 'arcs:indexChange', 0
      noNavigate: true
      replace: true

  indexChange: (id, index) ->
    index -= 1 if _.isNumeric(index)
    arcs.trigger 'arcs:indexChange', index,
      noNavigate: true
