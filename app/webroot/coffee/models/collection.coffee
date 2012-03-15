# collection.coffee
# -----------------
# Collection model
#
# Name might be confusing, but it refers to an ARCS Collection,
# which, for those playing at home, is also a server-side model with
# definable characteristics.
class arcs.models.Collection extends Backbone.Model
  defaults: 
    id: null
    title: 'Temporary Collection'
    description: ''
    public: false
    members: []

  urlRoot: arcs.baseURL + 'collections/create'
