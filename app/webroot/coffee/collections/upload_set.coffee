# upload_set.coffee
# -----------------
class arcs.collections.UploadSet extends Backbone.Collection
  model: arcs.models.Upload

  url: -> arcs.baseURL + 'uploads'
