# search.coffee
# -------------
# Extends Visual Search to interface with our ResultsSet object.
#
# It also can operate in 'headless' mode--returning results without needing
# a UI component.
# 
# The Search utility is a class, so there can be multiple instances of
# it at any time. Each instance maintains an arcs.collections.ResultSet
# object, which will hold any search results. When the run method is called, 
# the results will replace (or optionally, extend) the ResultSet object.
#
class arcs.utils.Search 

  # The constructor accepts options, but none are required for minimal
  # functionality.
  #
  # options
  #
  #   container: DOM Element that wraps the searchbar. When this is omitted,
  #              you get a headless search.
  #   query:     Starting query.
  #   loader:    Use arcs.utils.loader to display a loading gif while the
  #              results are being fetched. Off by default.
  #   run:       Call the run method after construct. Defaults to true.
  #   success:   Called after results are successfully fetched.
  #   error:     Called when fetching results fails.
  #   facets:    Set the facets property (this will rarely need to be 
  #              overriden)
  constructor: (options) ->

    defaults = 
      container: null
      query: ''
      loader: false
      run: true
      success: ->
      error: ->

    if options.facets?
      @facets = options.facets
    @options = _.extend defaults, options

    @query = @options.query
    @results = new arcs.collections.ResultSet

    @vs = VS.init
      container: @options.container
      query: @options.query
      callbacks:
        search: (query, searchCollection) =>
          # Update our query value
          @query = query
          # Call our run method with the facets
          @run searchCollection.toJSON()
        facetMatches: (callback) =>
          callback _.keys @facets
        valueMatches: (facet, searchTerm, callback) =>
          val = @facets[facet]
          if typeof val == 'function'
            @facets[facet] = val()
            callback @facets[facet]
          else 
            callback val

    if @options.run
      @run()

  # Convenience wrapper to call the VS.ui.SearchBox object's setQuery method.
  setQuery: (query) ->
    @vs.searchBox.setQuery query

  # Keys in the facets object are suggested as facets.
  # Values must be either an array, or a function that returns one.
  # If it's a function, the search term will be given as an argument.
  #
  # Keep in mind that the functions will be called with window scope.
  facets:
    access: ['public', 'private']
    filetype: -> 
      {value: k, label: v} for k,v of arcs.utils.mime.types()
    filename: []
    id: []
    sha: []
    # I've wrapped some of these in an anom func so that the load order
    # doesn't matter. Functions can also be given directly.
    title: -> arcs.utils.complete.title()
    user: -> arcs.utils.complete.user()
    tag: -> arcs.utils.complete.tag()
    collection: []
    created: -> arcs.utils.complete.created()
    uploaded: -> arcs.utils.complete.created()
    modified: -> arcs.utils.complete.modified()
    type: -> arcs.utils.complete.type()

  # Query the server and update the results collection.
  #
  # By default, no arguments are required. Facets will be taken from the
  # VS.searchBox object and the default options will be used.
  #
  # facets - array obj containing facet objects
  #
  # options 
  #
  #   add     - add the results to the collection, rather than resetting it.
  #   n       - number of results to return.
  #   page    - range of results to return. When n=30 and page=2, results
  #             31-60 are returned.
  #   success - callback to use instead of the instance's success callback.
  #   error   - callback to use instead of the instance's error callback.
  #
  # Returns an arcs.collections.ResultSet object, which is also accessible
  # via the `results` property.
  run: (facets, options) ->
    defaults =
      add: false
      n: 30
      page: 1
      success: @options.success
      error: @options.error
    options = _.extend defaults, options

    # Get the facets from the VS object if not given.
    if not facets? and @vs?
      facets = @vs.searchQuery.toJSON()

    # Don't want the app prop included.
    _.each facets, (f) ->
      delete f.app

    # Calculate the url parameters
    offset = (options.page - 1) * options.n
    params = "?n=#{options.n}&offset=#{offset}"

    arcs.utils.loader.show() if @options.loader

    @results.fetch
      add: options.add
      data: JSON.stringify(facets)
      type: 'POST'
      url: @results.url() + params
      contentType: 'application/json'
      success: =>
        options.success()
        arcs.utils.loader.hide() if @options.loader
      error: =>
        options.error()
        arcs.utils.loader.hide() if @options.loader

    @query = @vs.searchBox.value()

    @results
