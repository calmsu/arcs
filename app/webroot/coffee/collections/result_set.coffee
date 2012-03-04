# result_set.coffee
# -----------------
class arcs.collections.ResultSet extends Backbone.Collection
  model: arcs.models.Resource

  url: -> arcs.baseURL + 'search'
