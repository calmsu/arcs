# discussion.coffee
# -----------------
class arcs.collections.Discussion extends Backbone.Collection

  model: arcs.models.Comment

  url: ->
    arcs.baseURL + "resources/comments/" + arcs.resource.id

  parse: (response) ->
    (_.extend(r.User, r.Comment) for r in response)
