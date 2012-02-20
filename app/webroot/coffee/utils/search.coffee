# search.coffee
# -------------
# One stop shop for searching the ARCS catalog using Visual Search,
# or directly. This is largely a wrapper of Visual Search that bundles
# our facets and callbacks.
#
# The Search utility is a class, so there can be multiple instances of
# it at any time. Each instance maintains a results object, which is our
# own Backbone collection extension. When the run method is called, the
# results object will be reset with the new result set. (Say that 5 times
# fast.)
class arcs.utils.Search 

    # The constructor accepts options, but none are required for minimal
    # functionality.
    #
    # options
    #
    #   container: DOM Element that wraps the searchbar. When this is omitted,
    #              you get a headless search.
    #   query:     Starting query.
    #   useParams: Uses relevant url parameters in search query.
    #   run:       Call the run method after construct. Defaults to true.
    #   success:   Called after results are successfully fetched.
    #   error:     Called when fetching results fails.
    #   facets:    Set the facets property (this will rarely need to be 
    #              overriden)
    constructor: (options) ->

        defaults = 
            container: null
            query: ''
            useParms: true
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

    # Keys in the facets object are suggested as facets.
    # Values must be either an array, or a function that returns one.
    # If it's a function, the search term will be given as an argument.
    #
    # Keep in mind that the functions will be called with window scope.
    facets:
        access: ['public', 'private']
        filetype: -> 
            ({value: k, label: v} for k,v of arcs.utils.mime.types())
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

        if @options.useParams
            n = arcs.utils.params.get('n') ? 30
            offset = arcs.utils.params.get('offset') ? 0
            params = "?n=#{n}&offset=#{offset}"

        @results.fetch
            data: JSON.stringify(facets)
            type: 'POST'
            url: @results.url() + (params ? '')
            contentType: 'application/json'
            success: success or @options.success
            error: error or @options.error

        @query = @vs.searchBox.value()

        @results
