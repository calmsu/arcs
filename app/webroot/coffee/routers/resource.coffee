# resource.coffee
# ---------------
class arcs.routers.Resource extends Backbone.Router

  routes:
    ':id'       : 'noIndex'
    ':id/:index': 'indexChange'

  noIndex: (id) ->
    arcs.trigger 'arcs:indexChange', 0
    @initial = true

  indexChange: (id, index) ->
    index -= 1 if _.isNumeric(index)
    arcs.trigger 'arcs:indexChange', index, 
      noNavigate: true
    @initial = true
