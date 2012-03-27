# upload.coffee
# -------------
class arcs.models.Upload extends Backbone.Model
  defaults:
    name: null
    sha: null
    identifier: null
    progress: 0
    size: 0
    error: 0
    type: 'unknown'
    title: null
    lastModifiedDate: null
