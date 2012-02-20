# search.coffee
# -------------
# Search view
class arcs.views.Search extends Backbone.View

    initialize: ->
        @setupSelect()

        # Get the query
        query = arcs.utils.hash.get(uri=true) or null 

        # Set up search
        @search = new arcs.utils.Search 
            container: $('#search-wrapper')
            query: query
            # This callback will be fired each time a search is done.
            success: =>
                # Set the hash
                arcs.utils.hash.set @search.query, uri=true
                # Render the our results.
                @render()

        # Bind hotkeys:
        arcs.utils.keys.add 'a', true, @selectAll, @

    events:
        'dblclick img'           : 'openResult'
        'click img'              : 'selectResult'
        'click .result'          : 'unselectAll'
        'click #search-results'  : 'unselectAll'
        'click #open-btn'        : 'openSelected'
        'click #open-colview-btn': 'makeCollectionFromSelected'
        'click #collection-btn'  : 'makeCollectionFromSelected'
        'click #bookmark-btn'    : 'bookmarkSelected'
        'click #tag-btn'         : 'tagModal'
        'click #grid-btn'        : 'gridView'
        'click #list-btn'        : 'listView'

    # Set up drag to select
    setupSelect: ->
        $('#search-results').selectable
            # Little bit of drag tolerance
            distance: 20
            # Images are the selectables
            filter: 'img'
            # Make jQuery UI use our selected class
            selecting: (e, ui) ->
                $(ui.selecting).parent().addClass('selected')
            selected: (e, ui) ->
                $(ui.selected).parent().addClass('selected')
            unselecting: (e, ui) ->
                $(ui.unselecting).parent().removeClass('selected')
            unselected: (e, ui) ->
                $(ui.unselected).parent().removeClass('selected')

    results:
        selected: ->
            $('.result.selected')

        all: ->
            $('.result')

        select: (e) ->
            # If <ctrl> <shift> or <meta> is pressed allow multi-select
            if not (e.ctrlKey or e.shiftKey or e.metaKey)
                @unselectAll()
            $(e.currentTarget).parent('.result').toggleClass 'selected'

        toggle: (e) ->

        selectAll: ->
            @results.all().addClass 'selected'

        toggleAll: ->
            @results.all().toggleClass 'selected'

        unselectAll: (e) ->
            @results.all().removeClass 'selected'

        maybeUnselectAll: (e) ->
            if e?
                # If one of the modifier keys is held down, we won't do anything.
                if (e.metaKey or e.ctrlKey or e.shiftKey)
                    return false

                # If the target is the image, we won't do anything.
                if $(e.target).attr 'src'
                    return false

            @results.unselectAll()

        open: (e) ->
            # Allows calling with a jQuery Event (via the events hash)...
            if e instanceof jQuery.Event
                $el = $(e.currentTarget).parent()
                e.preventDefault()
            # ..or a DOM Element
            else
                $el = $(e)
            id = $el.find('img').attr('data-id')
            window.open(arcs.baseURL + 'resource/' + id)

        openSelected: ->
            that = @
            @results.selected().each ->
                that.openResult @ 

    # Create a new collection from the selected results, and open it.
    # Give it title and description arguments and we'll use those, otherwise
    # it's a 'Temporary collection' and the description is take from the search.
    makeCollectionFromSelected: (event) ->
        ids = _.map @getSelected().get(), (el) ->
            $(el).find('img').attr('data-id')

        description ?= "Results from search, '#{arcs.utils.hash.get(uri=true)}'"

        collection = new arcs.models.Collection
            public: false
            temporary: true
            members: ids

        collection.save description: description,
            success: (model) ->
                window.open(arcs.baseURL + 'collection/' + model.id)
            error: =>
                @notify()

    # Get the selected results. Use this rather than a raw selector so
    # that we can change everything in one place.
    getSelected: ->
        $('.result.selected')

    # Get all results.
    getAll: ->
        $('.result')

    # Unselect all.
    unselectAll: (e) ->
        # This can run with a jQuery.Event argument.

        # If one of the modifier keys is held down, we won't do anything.
        if e? and (e.metaKey or e.ctrlKey or e.shiftKey)
            return false

        # If the target is the image, we won't do anything.
        if e? and $(e.target).attr 'src'
            return false

        # Still here? Ok, unselect all results.
        @getSelected().removeClass 'selected'

    selectAll: ->
        @getAll().addClass 'selected'

    toggleAll: ->
        @getAll().toggleClass 'selected'

    # Select a result
    selectResult: (e) ->
        # If <ctrl> <shift> or <meta> is pressed allow multi-select
        if not (e.ctrlKey or e.shiftKey or e.metaKey)
            @unselectAll()
        $(e.currentTarget).parent('.result').toggleClass 'selected'

    # Open a result (in a new tab/window)
    openResult: (e) ->
        # Allows calling with a jQuery Event (via the events hash)...
        if e instanceof jQuery.Event
            $el = $(e.currentTarget).parent()
            e.preventDefault()
        # ..or a DOM Element
        else
            $el = $(e)
        id = $el.find('img').attr('data-id')
        window.open(arcs.baseURL + 'resource/' + id)

    # Open a modal (called when tag button is clicked) and ask
    # the user for a tag string. Delegate further action through 
    # callbacks.
    tagModal: ->
        n = @getSelected().length
        s = if n > 1 then 's' else ''

        # Unless something is selected:
        unless n
            alert "You must select at least 1 result to tag."
            return

        modal = new arcs.utils.Modal
            template: arcs.templates.searchModal
            templateValues:
                title: 'Tag Selected'
                message: "#{n} resource#{s} will be tagged."
            inputs: ['search-modal-value']
            backdrop: true
            buttons: 
                save: 
                    callback: @tagSelected
                    context: @
                cancel: ->

        # Focus the input
        modal.el.find('#search-modal-value').focus()

        arcs.utils.autocomplete
            sel: '#search-modal-value'
            source: arcs.utils.complete.tag()

    # Create a new Tag, given a result element and a string.
    tagResult: (el, tagStr) -> 
        id = $(el).find('img').attr('data-id')
        tag = new arcs.models.Tag
            resource_id: id
            tag: tagStr
        tag.save
            error: ->
                @notify 'Not authorized', 'error'

    # Call tagResult on all selected results.
    # This is used as a callback to arcs.modal
    tagSelected: (vals, tagStr) ->
        tagStr = tagStr ? vals['search-modal-value']
        n = @getSelected().length
        that = @
        @getSelected().each ->
            that.tagResult @, tagStr
        # Notify
        @notify "#{n} resources were tagged with #{tagStr}"

    # Create a new Bookmark, given a result element and optionally a note 
    # string.
    bookmarkResult: (el, noteStr=null) ->
        id = $(el).find('img').attr('data-id')
        bkmk = new arcs.models.Bookmark
            resource_id: id
            description: noteStr 
        bkmk.save
            error: ->
                @notify 'Not authorized', 'error'

    # Call bookmarkResult on all selected results.
    bookmarkSelected: ->
        n = @getSelected().length
        that = @
        @getSelected().each ->
            that.bookmarkResult @
        # Notify
        @notify "#{n} resources were bookmarked"

    # Open all selected results through @openResult()
    openSelected: ->
        that = @
        @getSelected().each ->
            that.openResult @ 

    notify: (msg, type='info') ->
        $notify = $('#search-notify')

        $notify.html(msg)
            .css('visibility', 'visible')
            .removeClass("alert-info alert-error alert-success")
            .addClass("alert-#{type}")

        window.setTimeout( 
            -> $notify.css('visibility', 'hidden'),
            2000
        )

    # Render the view using a grid view.
    gridView: ->
        $('#list-btn').removeClass 'active'
        $('#grid-btn').addClass 'active'
        @render()

    # Render the view using list view.
    listView: ->
        $('#grid-btn').removeClass 'active'
        $('#list-btn').addClass 'active'
        @render(list=true)

    # Render the view.
    render: (list=false) ->
        if list
            template = arcs.templates.resultsList
        else
            template = arcs.templates.resultsGrid
        $('#search-results').html Mustache.render template, 
            results: @search.results.toJSON()
