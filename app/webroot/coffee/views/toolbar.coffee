# toolbar.coffee
# --------------
# Toolbar view
#
# Manage toolbar buttons and bind them to funcs.
class arcs.views.Toolbar extends Backbone.View

    initialize: ->
        arcs.bind 'resourceChange', =>
            @buttonCheck()

        @addButton
            id: 'full-res'
            text: 'Full Resolution'
            class: 'image'

    events:
        'click .btn#full-res': 'openFullScreen'
        'click .btn#permalink': 'clipboardPermalink'
        'click .btn#split-pdf': 'splitPDF'
        'keyup #search': 'searchCheck'

    # Open the resource in a new window.
    openFullScreen: ->
        # delegate to the resource view
        arcs.resourceView.openFullScreen()

    searchCheck: (e) ->
        # stub

    # Copy the permalink to the clipboard
    clipboardPermalink: ->
        # stub
    
    # Schedule the resource for a split
    splitPDF: ->
        # stub

    # Add a button to the toolbar. Available options
    #   id: id attribute
    #   text: tag body
    #   url: href attribute
    #   class: class to apply to inner span 
    addButton: (options) ->
        @$el.find('#nav-container').append(Mustache.render(arcs.templates.button, options))

    hasButton: (id) ->
        @$el.find('#nav-container').children("##{id}").length > 0

    removeButton: (id) ->
        @$el.find('#nav-container').children("##{id}").remove()

    buttonCheck: ->
        if (arcs.utils.mime.getInfo(arcs.resource.get('mime_type')).ext == 'pdf')
            @addButton
                id: 'split-pdf'
                text: 'Split PDF'
                class: 'image'
        else 
            @removeButton 'split-pdf'
