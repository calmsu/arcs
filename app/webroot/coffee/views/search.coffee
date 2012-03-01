# search.coffee
# -------------
# Search View. Select and perform bulk actions on search results.
class arcs.views.Search extends Backbone.View

    ### Initialize and define events ###

    initialize: ->
        # Setup drag-to-select
        @setupSelect()

        # Setup search
        @setupSearch()

        # Setup our Router
        @router = new arcs.routers.Search
            search: @search

        # Start Backbone.history
        Backbone.history.start
            pushState: true
            root: arcs.baseURL + 'search/'
        
        # Search unless the Router already delegated it.
        unless @router.searched
            @search.run()

        # Grid view unless init-ed with false
        @grid ?= true

        ## Bind hotkeys
        
        # <ctrl>-a to select all
        arcs.utils.keys.add 'a', true, @_selectAll, @
        # <ctrl>-o to open selected
        arcs.utils.keys.add 'o', true, @openSelected, @

    events:
        'click img'              : '_select'
        'click .result'          : '_maybeUnselectAll'
        'click #search-results'  : '_maybeUnselectAll'
        'dblclick img'           : 'openResult'
        'click #open-btn'        : 'openSelected'
        'click #open-colview-btn': 'collectionFromSelected'
        'click #collection-btn'  : 'collectionFromSelected'
        'click #bookmark-btn'    : 'bookmarkSelected'
        'click #tag-btn'         : 'tagModal'
        'click #grid-btn'        : 'toggleView'
        'click #list-btn'        : 'toggleView'


    ### Methods that return result DOM els or alter their states ###
    
    ## No argument methods:
    
    _selected: -> $('.result.selected')
    _all: -> $('.result')
    _any: -> !!$('.result.selected').length
    _selectAll: -> @_all().addClass 'selected'
    _toggleAll: -> @_all().toggleClass 'selected'
    _unselectAll: -> @_all().removeClass 'selected'

    ## Methods that take an Event object:
    
    # Select a result and unselect everything else, unless a modifier key
    # is pressed.n
    _select: (e) ->
        # If <ctrl> <shift> or <meta> is pressed allow multi-select
        if not (e.ctrlKey or e.shiftKey or e.metaKey)
            @_unselectAll()
        $(e.currentTarget).parent('.result').toggleClass 'selected'

    # Unselect all results unless a modifier key is held down, or
    # the target isn't right.
    _maybeUnselectAll: (e) ->
        unless e instanceof jQuery.Event
            return @_unselectAll()
        # If one of the modifier keys is held down, we won't do anything.
        return false if (e.metaKey or e.ctrlKey or e.shiftKey)
        # If the target is the image, we won't do anything.
        return false if $(e.target).attr 'src'
        @_unselectAll()


    ### More involved setups run by the initialize method ###

    # Set up drag-to-select, using jQuery.ui.selectable
    setupSelect: ->
        @$el.find('#search-results').selectable
            # Little bit of drag tolerance
            distance: 20
            # Images are the selectables
            filter: 'img'
            # Make jQuery UI use our selected class
            selecting: (e, ui) ->
                $(ui.selecting).parent().addClass 'selected'
            selected: (e, ui) ->
                $(ui.selected).parent().addClass 'selected'
            unselecting: (e, ui) ->
                $(ui.unselecting).parent().removeClass 'selected'
            unselected: (e, ui) ->
                $(ui.unselected).parent().removeClass 'selected'

    # Make an instance of our Search utility and setup endless scrolling.
    setupSearch: ->
        @search = new arcs.utils.Search 
            container: $('#search-wrapper')
            run: false
            loader: true
            # This callback will be fired each time a search is done.
            success: =>
                @router.navigate(@search.query)
                # Render the our results.
                @render()

        @searchPage = 1

        $actions = $('#search-actions')
        $window = $(window)
        $results = $('#search-results')

        $window.scroll =>
            # Toggle the toolbar's fixed position
            if $window.scrollTop() > 160
                $actions.addClass 'toolbar-fixed' 
                $actions.width $results.width() + 22
            else
                $actions.removeClass 'toolbar-fixed'
                $actions.width 'auto'

            # If the scroll position is at the bottom, get the more results.
            if $window.scrollTop() == $(document).height() - $window.height()
                @searchPage += 1
                @search.run null,
                    add: true
                    page: @searchPage
                    success: =>
                        @append()

        # Fix the toolbar width on resizes. 
        # TODO: do this in the stylesheet.
        $window.resize ->
            if $window.scrollTop() > 160
                $actions.width $results.width() + 22


    ### Actions that take one or more search results ###
    
    # Open the resource view for a result.
    openResult: (e) ->
        # Allows calling with a jQuery Event (via the events hash)..
        if e instanceof jQuery.Event
            $el = $(e.currentTarget).parent()
            e.preventDefault()
        # ..or a DOM Element
        else
            $el = $(e)
        resource = @_getModel $el
        arcs.log resource
        window.open(arcs.baseURL + 'resource/' + resource.id)

    # Create a new Tag, given a result element and a string.
    tagResult: ($result, tagStr) -> 
        resource = @_getModel $result
        tag = new arcs.models.Tag
            resource_id: resource.id
            tag: tagStr
        tag.save
            error: ->
                arcs.utils.notify 'Not authorized', 'error'

    # Create a new Bookmark, given a result element and optionally a note 
    # string.
    bookmarkResult: ($result, noteStr=null) ->
        resource = @_getModel $result
        bkmk = new arcs.models.Bookmark
            resource_id: resource.id
            description: noteStr 
        bkmk.save
            error: ->
                arcs.utils.notify 'Not authorized', 'error'

    # Create a new collection from the selected results, and open it.
    collectionFromSelected: (event) ->
        ids = _.map @_selected().get(), ($el) =>
            @_getModel($el).id

        description ?= "Results from search '#{@search.query}'"

        collection = new arcs.models.Collection
            public: false
            temporary: true
            members: ids

        collection.save description: description,
            success: (model) ->
                window.open(arcs.baseURL + 'collection/' + model.id)
            error: =>
                arcs.utils.notify 'An error occurred.', 'error'

    # Open a modal (called when tag button is clicked) and ask the user for 
    # a tag string. Delegate further action through callbacks.
    tagModal: ->
        # Unless something is selected:
        unless @_any()
            return arcs.utils.notify "You must select a resource to tag", 
                'error'

        $modal = new arcs.utils.Modal
            template: 'searchModal'
            templateValues:
                title: 'Tag Selected'
                message: "#{@_selected().length} resource#{'s' if 0 < n > 1}" +
                         " will be tagged."
            inputs: ['search-modal-value']
            backdrop: true
            buttons: 
                save:
                    callback: @tagSelected
                    context: @
                cancel: ->

        # Focus the input
        $modal.el.find('#search-modal-value').focus()

        arcs.utils.autocomplete
            sel: '#search-modal-value'
            source: arcs.utils.complete.tag()

    # Call bookmarkResult on all selected results.
    bookmarkSelected: -> 
        @_doForSelected @bookmarkResult, ['bookmark', 'bookmarked']

    # Open all selected results through openResult
    openSelected: -> 
        @_doForSelected @openResult, ['open', 'opened']

    # Call tagResult on all selected results.
    # This is used as a callback to arcs.modal
    tagSelected: (vals, tagStr) ->
        tagStr = tagStr ? vals['search-modal-value']
        @_doForSelected @tagResult, tagStr, ['tag', 'tagged']

    # Find and return the Resource model that corresponds to a .result
    # DOM element.
    _getModel: ($result) ->
        id = $($result).find('img').attr 'data-id'
        @search.results.get id

    # Do something for each selected result. If nothing is selected,
    # display an error notification.
    _doForSelected: (cbk, cbkArgs..., name) ->
        unless @_any()
            return arcs.utils.notify 'You must select a resource to ' +
                name[0], 'error'

        @_selected().each (i, el) =>
            cbk.call @, el, cbkArgs...

        n = @_selected().length
        arcs.utils.notify "#{n} resource#{'s' if 0 < n > 1} " +
            "#{if 0 < n > 1 then "were" else "was"} #{name[1]}", 'success'

    # Toggle between list and grid view.
    toggleView: ->
        @grid = !@grid
        $('#grid-btn').toggleClass 'active'
        $('#list-btn').toggleClass 'active'
        @render()


    ### Render the search results ###

    # Append more results. 
    #
    # We do this instead of a full render to stop the scrollbar from 
    # jumping in certain browsers.
    append: ->
        # Get new results after the ones already displayed.
        rest = @search.results.rest @_all().length 
        results = new arcs.collections.ResultSet rest
        @_render results: results.toJSON(), true

    # Render the results.
    render: ->
        @_render results: @search.results.toJSON()

    _render: (results, append=false) ->
        $results = $('#search-results')
        template = if @grid then 'resultsGrid' else 'resultsList'
        content = arcs.tmpl template, results
        if append
            $results.append content
        else
            $results.html content
        unless @_all().length
            $results.html '<div id="no-results">No Results</div>'
