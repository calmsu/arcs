# search.coffee
# -------------
class arcs.routers.Search extends Backbone.Router

    initialize: (options) ->
        @search = options.search

    routes:
        ':query': 'doSearch'

    doSearch: (query='') ->
        unless query == 'search'
            @search.setQuery query
            @search.run()
            @navigate(@search.query)
            @searched = true
            return @searched
        @navigate '/'
