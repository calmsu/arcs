# collection.coffee
# -----------------
# This is our standard collection for holding resources.
class arcs.collections.Collection extends Backbone.Collection

  model: arcs.models.Resource

  comparator: (resource) ->
    resource.get 'page'
