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

  # TODO: fix base url
  urlRoot: arcs.baseURL + 'collections/add'
