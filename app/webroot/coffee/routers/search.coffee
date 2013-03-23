# search.coffee
# -------------
class arcs.routers.Search extends Backbone.Router

  initialize: (options) ->
    @search = options.search

  routes:
    ''              : 'root'
    'p:page'        : 'emptyWithPage'
    ':query/p:page' : 'doSearch'
    ':query'        : 'doSearch'

  # If the '' route is matched, the url is 'search/'
  # A slash-less 'search' won't be matched, so we need to make note
  # of this so we can replace the url later.
  root: ->
    @hasTrailing = true and @doSearch()

  # Wrap Router.navigate to fix our trailing-slash issue.
  navigate: (fragment, options) ->
    arcs.log 'navigate', fragment
    options or= {}
    unless @hasTrailing
      options.replace = true
      @hasTrailing = true
    super fragment, options

  emptyWithPage: (page) ->
    if /\d+/.test(page)
      @doSearch('', page)
    else
      @doSearch(page)

  # Run the search and set the url.
  doSearch: (query = '', page = 1) ->
    return @navigate('//p1', replace: true) if query == 'search'

    @search.setQuery query
    @search.options.page = parseInt page
    @search.run()
    @navigate "#{encodeURIComponent(@search.query)}/p#{page}"
    return @searched = true
