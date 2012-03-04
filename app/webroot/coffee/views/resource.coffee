# resource.coffee
# ---------------
# Resource view
#
# Handles the rendering of the Resource, the tables, ...
class arcs.views.Resource extends Backbone.View

  # View container
  el: $('#resource-wrapper')

  # Setup
  initialize: ->
    # Bind resourceChange event to render
    arcs.bind 'resourceChange', =>
      arcs.utils.hash.set @index + 1
      @render()

    # Init our sub-views
    arcs.discussionView = new arcs.views.Discussion
      el: $('#discussion')
    arcs.tagView = new arcs.views.Tag
      el: $('#information')
    arcs.hotspotView = new arcs.views.Hotspot
      el: $('#resource')
    arcs.toolbarView = new arcs.views.Toolbar
      el: $('#toolbar')

    @index = (arcs.utils.hash.get() or 1) - 1
    # Set the resource
    @setResource(@index) or @render()
    # Setup the thumbnail carousel
    @_setupCarousel(@index)

    # Bind key events (see utils/keys.coffee)
    arcs.utils.keys.add 'left', false, @prevResource, @
    arcs.utils.keys.add 'right', false, @nextResource, @

    # Bind model alter events to render
    @model.bind 'change', =>
      @render
    @model.bind 'destroy', =>
      @render

    if @model.get 'first_req'
      @firstReq()

  # Set up the events.
  events:
    'dblclick img': 'openFullScreen'
    'click #next-button': 'nextResource'
    'click #prev-button': 'prevResource'

  # Start the thumbnail carousel.
  _setupCarousel: (index) ->
    $('#carousel').elastislide
      imageW: 100
      onClick: ($item) =>
        id = $item.find('img').attr('data-id')
        @setResourceById(id)
    @setCarousel index

  # Open the resource in a new window.
  openFullScreen: ->
    window.open @model.get('url'), '_blank', 'menubar=no'

  # Swap the active model with the next model (by index) in the collection
  # array.
  nextResource: ->
    if @collection.length > @index + 1
      @index += 1
      @swapModel @collection.at(@index)
      @setCarousel @index

  # Swap the active model with the previous model (by index) in the collection
  # array.
  prevResource: ->
    if @index > 0
      @index -= 1
      @swapModel @collection.at(@index)
      @setCarousel @index

  # Set the model by collection index.
  setResource: (index) ->
    if @collection.length > index + 1 and index >= 0
      @swapModel @collection.at(index)
      @index = index

  # Set the model by id.
  setResourceById: (id) ->
    @swapModel @collection.get(id)

  # Get and set the index of the model relative to the collection. 
  setIndex: ->
    @index = @collection.indexOf(@model)

  setCarousel: (index) ->
    $('#carousel').elastislide('slideToIndex', index)
      
  # Check to see if either navigation button should be disabled.
  checkNavigation: ->
    if @collection.length == @index + 1
      $('#next-button').addClass 'disabled'
    else
      $('#next-button').removeClass 'disabled'
    if @index == 0
      $('#prev-button').addClass 'disabled'
    else
      $('#prev-button').removeClass 'disabled'

  # Swap the model and the 'arcs.resource' property in one swoop.
  # Also triggers the 'resourceChange' event, which other views rely on.
  swapModel: (model) ->
    @model = model
    @setIndex()
    arcs.resource = model
    arcs.trigger 'resourceChange'

  # Add the 'selected' class to the thumbnail that corresponds to the
  # current model.
  setThumbSelected: ->
    $carousel = $('#carousel')
    $carousel.find('.thumb').removeClass 'selected'
    $carousel.find(".thumb[data-id=#{@model.id}]").addClass 'selected'


  firstReq: ->
    if @model.get('mime_type') == 'application/pdf'
      new arcs.views.Modal
        title: "Split into a PDF?"
        subtitle: "We noticed you've uploaded a PDF. If you'd like, " +
          "we can split the PDF into a collection, where it can be " +
          "annotated and commented on--page by page."
        buttons:
          yes: =>
            $.get(arcs.baseURL + 'resources/pdfSplit/' + @model.id)
          no: ->

  # Render the view.
  # This means rendering the resource and table templates and calling some
  # of the view's methods.
  render: ->
    $resource = @$el.find('#resource')
    $table = @$el.find('#resource-details')
    $ctable = @$el.find('#collection-details')

    $resource.html('')
    type = arcs.utils.mime.getInfo(@model.get('mime_type')).type

    # Resource is rendered based on its type.
    if type == 'image'
      $resource.html arcs.tmpl 'resource/image', @model.toJSON()
    else if type == 'document'
      $resource.html arcs.tmpl 'resource/document', @model.toJSON()
    else
      $resource.html 'Unknown resource type.'
    arcs.trigger 'resourceLoaded'

    # Render the details tables.
    $table.html arcs.tmpl 'resource/table', @model.toJSON()
    if @collection.length
      $ctable.html arcs.tmpl 'resource/collection_table', 
        arcs.collectionData

    # Check the navigation.
    @checkNavigation()
    # Set the thumb.
    @setThumbSelected()
    # Return `this` for chaining (just in case).
    @
