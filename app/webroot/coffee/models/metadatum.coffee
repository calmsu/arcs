# metadata_container.coffee
# ----------------
class arcs.models.MetadataContainer extends Backbone.Model

  url: ->
    arcs.baseURL + 'resources/metadata/' + @id
