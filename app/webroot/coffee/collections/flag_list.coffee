# flag_list.coffee
# ----------------
class arcs.collections.FlagList extends Backbone.Collection

  model: arcs.models.Flag

  url: ->
    arcs.baseURL + "resources/flags/" + arcs.resource.id

  parse: (response) ->
    r.Flag for r in response
