# resource.coffee
# ---------------
class arcs.routers.Resource extends Backbone.Router

  routes:
    ':id/:index': 'change'

  change: (id, index) ->
    arcs.log id, index
