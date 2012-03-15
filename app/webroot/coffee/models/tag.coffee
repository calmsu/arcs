# tag.coffee
# ----------
class arcs.models.Tag extends Backbone.Model
  urlRoot: arcs.baseURL + 'tags'

  validate: (attrs) ->
    # Check uniqueness (as far as we know client-side)
    if arcs.tagView?
      tags = arcs.tagView.collection.pluck 'tag'
      tags.push attrs.tag
