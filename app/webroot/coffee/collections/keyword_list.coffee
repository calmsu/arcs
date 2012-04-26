# keyword_list.coffee
# -------------------
class arcs.collections.KeywordList extends Backbone.Collection

  model: arcs.models.Keyword

  url: ->
    arcs.baseURL + "resources/keywords/" + arcs.resource.id

  parse: (response) ->
    keywords = (r.Keyword for r in response)
    for k in keywords
      k.link = arcs.baseURL + "search/" + encodeURIComponent "keyword: '#{k.keyword}'"
    keywords
