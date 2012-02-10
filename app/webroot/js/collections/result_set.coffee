# result_set.coffee
# -----------------
class arcs.collections.ResultSet extends Backbone.Collection
    model: arcs.models.Resource

    url: ->
        arcs.baseURL + 'search/'

    parse: (response) ->
        resources = []
        for r in response
            user_name = r.User.name
            r = r.Resource
            r.user_name = user_name
            if r.modified == r.created
                r.modified = null
            resources.push r
        resources
