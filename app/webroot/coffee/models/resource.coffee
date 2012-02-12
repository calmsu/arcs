# resource.coffee
# ---------------
# Resource model
class arcs.models.Resource extends Backbone.Model
    defaults:
        id: null
        mime_type: "image/png"
        modified: null
        created: null
        public: false

    urlRoot: arcs.baseURL + 'resources'

    parse: (response) ->
        if response.modified = response.created
            response.modified = null
        response
