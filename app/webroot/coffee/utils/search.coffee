# search.coffee
# -------------
# One stop shop for searching the ARCS catalog using Visual Search,
# or directly. This is largely a wrapper of Visual Search that bundles
# our facets and callbacks.
#
# The Search utility is a class, so there can be multiple instances of
# it at any time. Each instance maintains a results object, which is our
# own Backbone collection extension. When the run method is called, the
# results object will be reset with the new result set.
class arcs.utils.Search 

    # The constructor accepts options, but none are required for minimal
    # functionality.
    #
    # options
    #
    #   container: DOM Element that wraps the searchbar. When this is omitted,
    #              you get a headless search.
    #   query:     Starting query.
    #   run:       Call the run method after init. Defaults to true.
    #   success:   Called after results are successfully fetched.
    #   error:     Called when fetching results fails.
    #   facets:    Set the facets property (this will rarely need to be 
    #              overriden)
    constructor: (options) ->

        defaults = 
            container: null
            query: ''
            run: true
            success: ->
            error: ->

        if options.facets?
            @facets = options.facets
        @options = _.extend defaults, options

        @results = new arcs.collections.ResultSet

        @vs = VS.init
            container: @options.container
            query: @options.query
            callbacks:
                search: (query, searchCollection) =>
                    @run searchCollection.toJSON()
                facetMatches: (callback) =>
                    callback _.keys @facets
                valueMatches: (facet, searchTerm, callback) =>
                    val = @facets[facet]
                    if typeof val == 'function'
                        callback val()
                    else 
                        callback val

        if options.run
            @run()

    # Keys in the facets object are suggested as facets.
    # Values must be either an array, or a function that returns one.
    #
    # Keep in mind that the functions will be called with window scope.
    facets:
        # Note that here we wrap the func inside of an anonymous func
        # so that script load order won't matter. Providing the func object 
        # itself is also ok.
        filetype: -> arcs.utils.mime.types()
        filename: []
        sha: []
        title: -> arcs.utils.complete.titles()
        user: -> arcs.utils.complete.users()
        tag: -> arcs.utils.complete.tags()
        collection: []
        date: []

    # Query the server and update the results collection.
    #
    # By default, no arguments are required. The current Visual Search
    # query and the instance's given or default callbacks will be used.
    #
    # If you'd like, you can override the instance's facets and
    # provide different ones. The same goes for the success and error
    # callbacks.
    #
    # An arcs.collections.ResultSet object will be returned.
    run: (facets, success, error) ->
        if not facets? and @vs?
            facets = @vs.searchQuery.toJSON()

        _.each facets, (f) ->
            delete f.app

        @results.fetch
            data: JSON.stringify(facets)
            type: 'POST'
            contentType: 'application/json'
            success: success or @options.success
            error: error or @options.error

        @results
