# search.coffee
# -------------
# Search view
class arcs.views.Search extends Backbone.View

    initialize: ->
        $('.btn[rel=tooltip]').tooltip
            placement: 'bottom'

        @setupSelect()

        # Get the query
        query = arcs.utils.hash.get() or null 

        # Set up search
        @search = new arcs.utils.Search 
            container: $('#search-wrapper')
            query: query
            success: =>
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

    # Create a new collection from the selected results, and open it.
    # Give it title and description arguments and we'll use those, otherwise
    # it's a 'Temporary collection' and the description is take from the search.
    makeCollectionFromSelected: (event=null, title=null, description=null) ->
        ids = ($(el).find('img').attr('data-id') for el in @.getSelected().get())

        title = title ? 'Temporary collection'
        description = description ? "Results from search, '#{arcs.utils.hash.get()}'"

        arcs.log title, description

        collection = 
            Collection:
                title: title
                description: description
                public: false
                temporary: true
            Members: ids

        $.ajax
            url: arcs.baseURL + 'collections/create'
            data: JSON.stringify(collection)
            type: 'POST'
            contentType: 'application/json'
            success: (data) =>
                window.open(arcs.baseURL + 'collection/' + data.id)
            error: =>
                @notify "Not authorized", 'error'

    # Get the selected results. Use this rather than a raw selector so
    # that we can change everything in one place.
    getSelected: ->
        $('.result.selected')

    # Get all results.
    getAll: ->
        $('.result')

    # Unselect all.
    unselectAll: (e=null) ->
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
            @.unselectAll()
        $(e.currentTarget).parent('.result').toggleClass 'selected'

    # Open a result (in a new tab/window)
    openResult: (e) ->
        arcs.log 'called'
        # Allows calling with a jQuery Event (via Backbone)...
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

        # If nothing is selected:
        if n == 0
            alert "You must select at least 1 result to tag."
            return

        arcs.utils.modal
            template: arcs.templates.searchModal
            templateValues:
                title: 'Tag Selected'
                message: "#{n} resource#{s} will be tagged."
            inputs: ['search-modal-value']
            backdrop: true
            buttons: 
                save:
                    callback: @.tagSelected
                    context: @

        $('#search-modal-value').focus()

        arcs.utils.autocomplete
            sel: '#search-modal-value'
            source: arcs.utils.complete.tags()

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
    tagSelected: (vals, modal, tagStr=null) ->
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

    # Open all selected results through @.openResult()
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
