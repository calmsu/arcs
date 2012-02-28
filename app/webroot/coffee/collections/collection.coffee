# collection.coffee
# -----------------
class arcs.collections.Collection extends Backbone.Collection
    model: arcs.models.Resource

    parse: (response) ->
        for r in response
            if r.modified == r.created
                r.modified = null
        response
