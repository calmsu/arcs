# keyword.coffee
# --------------
class arcs.models.Keyword extends Backbone.Model

  url: ->
    return arcs.baseURL + 'keywords' if @isNew()
    arcs.baseURL + "keywords/#{@id}"
