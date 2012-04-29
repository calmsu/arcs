# resource.coffee
# ---------------
# Resource model
class arcs.models.Resource extends Backbone.Model

  defaults:
    title: ''
    keywords: []
    annotations: []
    comments: []
    metadata: {}
    mime_type: "unknown"
    page: 0
    preview: false
    public: false
    selected: false

  constructor: (attributes) ->
    super @parse attributes

  url: -> 
    arcs.baseURL + 'resources/' + @id

  urlRoot: arcs.baseURL + 'resources'

  parse: (r) ->
    # Flatten an HABTM object.
    if r.Resource?
      for k, v of r.Resource
        r[k] = v
      if r.User?
        r.user = r.User
        delete r.User
      if r.Keyword?
        r.keywords = (k.keyword for k in r.Keyword)
        delete r.Keyword
      if r.Comment?
        r.comments = r.Comment
        delete r.Comment
      if r.Flag?
        r.flags = r.Flag
        delete r.Flag
      if r.Membership?
        r.memberships = {}
        r.memberships[m.collection_id] = parseInt(m.page) for m in r.Membership
        delete r.Membership
      if r.Annotation?
        r.annotations = r.Annotation
        delete r.Annotation
      if r.Metadatum?
        r.metadata = new arcs.models.MetadataContainer
        r.metadata.id = r.id
        r.metadata.set(m.attribute, m.value) for m in r.Metadatum
        delete r.Metadatum
      delete r.Resource

    # If modified == created, it wasn't modified.
    r.modified = false if r.modified is r.created

    # All done.
    return r
