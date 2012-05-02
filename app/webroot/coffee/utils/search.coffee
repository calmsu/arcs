# search.coffee
# -------------
# Extends Visual Search to interface with our ResultsSet object.
class arcs.utils.Search extends Backbone.View

  options:
    container : null
    query     : ''
    loader    : false
    order     : 'modified'
    direction : 'desc'
    page      : 1
    n         : 30
    add       : false
    run       : true
    onSearch  : ->
    success   : ->
    error     : ->

  initialize: ->
    [@query, @page] = [@options.query, @options.page]
    @collection = @results = new arcs.collections.ResultSet
    @vs = VS.init
      container: @options.container
      query: @query
      callbacks:
        search: (query, searchCollection) =>
          @query = query # Update our query value
          @options.onSearch query # Fire the onSearch cb
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
    @run() if @options.run

  # Convenience wrapper to call the VS.ui.SearchBox object's setQuery method.
  setQuery: (query) ->
    @vs.searchBox.setQuery query

  # Keys in the facets object are suggested as facets.  Values must be either
  # an array, or a function that returns one. If it's a function, the search
  # term will be given as an argument.
  facets:
    access     : ['public', 'private']
    filetype   : -> {value: k, label: v} for k,v of arcs.utils.mime.types()
    filename   : []
    id         : []
    sha        : []
    title      : -> arcs.complete 'resources/complete/title'
    user       : -> arcs.complete 'users/complete'
    keyword    : -> arcs.complete 'keywords/complete'
    collection : -> arcs.complete 'collections/complete'
    created    : -> arcs.completeDate 'resources/complete/created'
    uploaded   : -> arcs.completeDate 'resources/complete/created'
    modified   : -> arcs.completeDate 'resources/complete/modified'
    type       : -> _.compact _.keys arcs.config.types

  getLast: ->
    @results.rest (@results.length % @options.n or @options.n)

  # Fetch the given query, or, if none is given, the current query in the
  # Visual Search box. Pass an options hash to override the the object's
  # options.
  run: (query, options) ->
    # Use this.options for default opts, but don't alter it.
    options = _.extend _.clone(@options), options

    params = "?related&n=#{options.n}&page=#{options.page}" +
      "&order=#{options.order}&direction=#{options.direction}"

    query ?= @vs.searchQuery.toJSON()
    delete q.app for q in query

    arcs.loader.show() if options.loader

    @results.fetch
      add: options.add
      data: JSON.stringify query
      type: 'POST'
      url: @results.url() + params
      contentType: 'application/json'
      success: =>
        options.success()
        arcs.loader.hide() if options.loader
      error: =>
        options.error()
        arcs.loader.hide() if options.loader

    @query = @vs.searchBox.value()
    @page = options.page
    @results
