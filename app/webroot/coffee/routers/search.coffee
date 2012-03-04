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

    # We're fixing the url state here if the query is 'search'. That 
    # probably means that the url wasn't loaded with a trailing slash. 
    # Only caveat is that it's now impossible to search for 'search'.
    @navigate '/'
