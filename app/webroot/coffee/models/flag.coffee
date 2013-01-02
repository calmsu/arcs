# flag.coffee
# -----------
# Flag model
class arcs.models.Flag extends Backbone.Model
  urlRoot: arcs.baseURL + 'flags'

  constructor: (attributes) ->
    super @parse attributes

  parse: (f) ->
    if f.Flag?
      for k, v of f.Flag
        f[k] = v
        delete f.Flag
      if f.User?
        f.user = f.User
        delete f.User
      if f.Resource?
        f.resource = f.Resource
        delete f.Resource
    f
