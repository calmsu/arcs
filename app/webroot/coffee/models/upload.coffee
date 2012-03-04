# upload.coffee
# -------------
class arcs.models.Upload extends Backbone.Model
  defaults:
    id: null
    fileName: null
    lastModifiedDate: null
    progress: 0
    size: 0
    type: 'unknown'
