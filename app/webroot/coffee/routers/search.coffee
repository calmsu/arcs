# search.coffee
# -------------
class arcs.routers.Search extends Backbone.Router

  initialize: (options) ->
    @search = options.search

  routes:
    ''      : 'root'
    ':query': 'doSearch'

  # If the '' route is matched, the url is 'search/'
  # A slash-less 'search' won't be matched, so we need to make note
  # of this so we can replace the url later.
  root: ->
    @hasTrailing = true and @doSearch()

  # Wrap Router.navigate to fix our trailing-slash issue.
  navigate: (fragment, options) ->
    options or= {}
    unless @hasTrailing
      options.replace = true
      @hasTrailing = true
    super fragment, options

  # Run the search and set the url.
  doSearch: (query='') ->
    @search.setQuery query
    @search.run()
    @navigate encodeURIComponent @search.query
    return @searched = true
