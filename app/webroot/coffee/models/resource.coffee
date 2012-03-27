# resource.coffee
# ---------------
# Resource model
class arcs.models.Resource extends Backbone.Model
  defaults:
    id: null
    mime_type: "unknown"
    modified: null
    created: null
    preview: false
    public: false
    selected: false

  constructor: (attributes) ->
    super @parse attributes

  url: -> arcs.baseURL + 'resources/' + @id
  urlRoot: arcs.baseURL + 'resources'

  # List of attributes that the server will accept updates to.
  MODIFIABLE: [
    'identifier',
    'copyright',
    'creator',
    'location',
    'subject'
    'coverage',
    'date',
    'format',
    'date-modified',
    'language',
    'description',
    'medium'
  ]

  SINGULAR: [
    'identifier'
  ]

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
        r.memberships = m.collection_id for m in r.Membership
        delete r.Membership
      if r.Hotspot?
        r.hotspots = r.Hotspot
        delete r.Hotspot
      if r.Metadatum?
        r.metadata = {}
        r.metadata[m.attribute] = m.value for m in r.Metadatum
        delete r.Metadatum
      delete r.Resource

    # If modified == created, it wasn't modified.
    r.modified = false if r.modified is r.created

    # Make the file_size readable.
    r.file_size = arcs.utils.convertBytes r.file_size

    # All done.
    return r
