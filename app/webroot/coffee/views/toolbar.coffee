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
    'click #thumbs-btn': 'openInSearch'

  # Open the resource in a new window.
  openFullScreen: ->
    # delegate to the resource view
    arcs.viewer.openFullScreen()

  # Copy the permalink to the clipboard
  clipboardPermalink: ->
    # stub
  
  openInSearch: ->
    arcs.viewer.openInSearch()

  # Add a button to the toolbar. Available options
  #   id: id attribute
  #   text: tag body
  #   url: href attribute
  #   class: class to apply to inner span 
  addButton: (options) ->
    @$el.find('#nav-container').append arcs.tmpl 'ui/button', options

  hasButton: (id) ->
    @$el.find('#nav-container').children("##{id}").length > 0

  removeButton: (id) ->
    @$el.find('#nav-container').children("##{id}").remove()

  buttonCheck: ->
    if arcs.utils.mime.getInfo(arcs.resource.get('mime_type')).ext == 'pdf'
      @addButton
        id: 'split-pdf'
        text: 'Split PDF'
        class: 'image'
    else 
      @removeButton 'split-pdf'
