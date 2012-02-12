# tag_list.coffee
# ---------------
class arcs.collections.TagList extends Backbone.Collection

    model: arcs.models.Tag

    url: ->
        arcs.baseURL + "resources/tags/" + arcs.resource.id

    parse: (response) ->
        return (r.Tag for r in response)
