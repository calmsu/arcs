# collection.coffee
# -----------------
# The name might be confusing, but it refers the server-side model
# which maintains information about a resource collection (e.g. title,
# description)
class arcs.models.Collection extends Backbone.Model
  defaults: 
    id: null
    title: 'Temporary Collection'
    description: ''
    public: false
    members: []

  constructor: (attributes) ->
    super @parse attributes

  # TODO: fix base url
  urlRoot: arcs.baseURL + 'collections/add'

  parse: (c) ->
    # Flatten an HABTM object.
    if c.Collection?
      for k, v of c.Collection
        c[k] = v
      delete c.Collection
    c
