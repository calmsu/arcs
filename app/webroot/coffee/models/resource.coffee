# resource.coffee
# ---------------
# Resource model
class arcs.models.Resource extends Backbone.Model
  defaults:
    id: null
    mime_type: "unknown"
    modified: null
    created: null
    public: false

  urlRoot: arcs.baseURL + 'resources'

  parse: (r) ->
    # Flatten an HABTM object.
    if r.Resource?
      for k, v of r.Resource
        r[k] = v
      if r.User?
        r.user = r.User
        delete r.User
      if r.Tag?
        r.tags = t.tag for t in r.Tag
        delete r.Tag
      if r.Comment?
        r.comments = r.Comment
        delete r.Comment
      if r.Membership?
        r.memberships = m.collection_id for m in r.Membership
        delete r.Membership
      if r.Hotspot?
        r.hotspots = r.Hotspot
        delete r.Hotspot
      delete r.Resource

    # If modified == created, it wasn't modified.
    r.modified = false if r.modified is r.created

    # Make the file_size readable.
    r.file_size = arcs.utils.convertBytes r.file_size

    # All done.
    return r
