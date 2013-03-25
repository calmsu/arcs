# search.coffee
# -------------
# Extends Visual Search to interface with our ResultsSet object.
class arcs.utils.Search extends Backbone.View

  options:
    container : null
    query     : ''
    loader    : false
    order     : 'modified'
    direction : 'asc'
    page      : 1
    n         : 25 
    add       : false
    run       : true
    onSearch  : ->
    success   : ->
    error     : ->

  # Keys in the facets object are suggested as facets.  Values must be either
  # an array, or a function that returns one. If it's a function, the search
  # term will be given as an argument.
  facets:
    id         : []
    sha        : []
    text       : []
    access     : ['public', 'private']
    # identifier : arcs.completeFacet
    # language   : arcs.completeFacet
    # location   : arcs.completeFacet
    # subject    : arcs.completeFacet
    # medium     : arcs.completeFacet
    # format     : arcs.completeFacet
    # creator    : arcs.completeFacet
    filetype   : arcs.completeFacet
    filename   : arcs.completeFacet
    title      : arcs.completeFacet
    user       : arcs.completeFacet
    keyword    : arcs.completeFacet
    #collection : arcs.completeFacet
    type       : arcs.completeFacet
    created    : -> arcs.completeDate 'resources/complete/created'
    uploaded   : -> arcs.completeDate 'resources/complete/created'
    modified   : -> arcs.completeDate 'resources/complete/modified'

  initialize: ->
    [@query, @page] = [@options.query, @options.page]
    @collection = @results = new arcs.collections.ResultSet
    @vs = VS.init
      container: @options.container
      query: @query
      callbacks:
        search: (query, searchCollection) =>
          @query = query # Update our query value
          @options.page = 1
          @options.onSearch query # Fire the onSearch cb
          @run()
        facetMatches: (callback) =>
          callback _.keys @facets
        valueMatches: (facet, searchTerm, callback) =>
          val = @facets[facet]
          if typeof val == 'function'
            callback val(facet, encodeURIComponent(@query))
          else 
            callback val
    @run() if @options.run

  # Convenience wrapper to call the VS.ui.SearchBox object's setQuery method.
  setQuery: (query) ->
    @vs.searchBox.setQuery query

  getLast: ->
    @results.last (@results.length % @options.n or @options.n)

  # Fetch the given query, or, if none is given, the current query in the
  # Visual Search box. Pass an options hash to override the the object's
  # options.
  run: (query, options) ->
    # Use this.options for default opts, but don't alter it.
    options = _.extend _.clone(@options), options
    query ?= @vs.searchBox.value()

    params = "?related&n=#{options.n}" +
      "&page=#{options.page}" +
      "&order=#{options.order}" +
      "&direction=#{options.direction}"

    if query 
      params += "&q=#{encodeURIComponent(query)}"

    arcs.loader.show() if options.loader

    @results.fetch
      add: options.add
      url: @results.url() + params
      success: (set, res) =>
        @results.query = res
        options.success()
        arcs.loader.hide() if options.loader
      error: =>
        options.error()
        arcs.loader.hide() if options.loader

    @query = @vs.searchBox.value()
    @page = options.page
    @results
