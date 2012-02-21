# search.coffee
# -------------
class arcs.routers.Search extends Backbone.Router

    initialize: (options) ->
        @search = options.search

    routes:
        ':query': 'doSearch'

    doSearch: (query) ->
        @search.setQuery query
        @search.run()
        @navigate(@search.query)
        @searched = true
