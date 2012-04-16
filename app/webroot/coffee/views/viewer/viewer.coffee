# viewer.coffee
# -------------
class arcs.views.Viewer extends Backbone.View

  initialize: ->
    @collectionModel = @options.collectionModel

    @orderCollection()

    # Set the resource on the `arcs:indexChange` event.
    # This may be triggered by the router or the Carousel view.
    arcs.on 'arcs:indexChange', @set, @

    @collection.on 'add change remove', @render, @
    @model.on 'add change remove', @render, @

    $(window).resize => arcs.trigger 'arcs:resourceResize'
    arcs.on 'arcs:resourceResize', @resize, @

    # Add our hotkeys
    arcs.keys.add 'left', false, @prev, @
    arcs.keys.add 'right', false, @next, @

    # Init sub-views
    @actions = new arcs.views.ViewerActions
      el: $('#viewer-controls')
      collection: @collection
      viewer: @
    @discussion = new arcs.views.Discussion
      el: $('#discussion')
    @keywords = new arcs.views.Keyword
      el: $('#information')
    @hotspots = new arcs.views.Hotspot
      el: $('#resource')
    @toolbar = new arcs.views.Toolbar
      el: $('#toolbar')
    @carousel = new arcs.views.Carousel
      el: $('#carousel-wrapper')
      collection: @collection
      index: @index ? 0

    # Start the router and Backbone.history
    @router = new arcs.routers.Resource
    Backbone.history.start
      pushState: true
      root: arcs.baseURL + 
        if @collection.length then 'collection/' else 'resource/'

    # Special logic for a resource's first request post-upload.  
    @splitPrompt() if @model.get 'first_req'

    @index ?= 0
    @resize()

  events:
    'dblclick img'      : 'openFull'
    'click #next-btn'   : 'next'
    'click #prev-btn'   : 'prev'

  orderCollection: ->
    return unless @collectionModel.id?
    @collection.each (resource) =>
      resource.set 'page', resource.get('memberships')[@collectionModel.id]
    @collection.sort()

  # Set the resource, given an identifier.
  #
  #  identifier - resource id or index within the collection
  #
  #  options -
  #    trigger    - if true, trigger an indexChange event.
  #    noSet      - used internally when triggering the indexChange event
  #                 to no-op when also bound to that event.
  #    noRender   - don't call the render method.
  #    noNavigate - don't call router.navigate()
  #
  set: (identifier, options={}) ->
    return false if options.noSet

    # If it's numeric, assume we were given an index.
    if _.isNumeric(identifier)
      index = parseInt(identifier)
      model = if @collection.length then @collection.at index else @model
    # Otherwise, assume we were given an id.
    else
      model = @collection.get identifier
      index = @collection.models.indexOf model
      options.noNavigate = false

    # Can't continue without a valid model and index.
    return false unless model and index >= 0

    # Replace the instance's model and index properties.
    [@model, arcs.resource, @index] = [model, model, index]

    # Trigger indexChange if the trigger opt was set.
    arcs.trigger('arcs:indexChange', index, noSet: true) if options.trigger
    @render() unless options.noRender
    # Update the location
    route = "#{arcs.collectionModel?.id ? @model.id}/#{@index + 1}"
    @router.navigate(route) unless options.noNavigate
    @_preloadNeighbors() unless options.noPreload
    # Return true if we were able to set the model and index.
    return true

  # Preload the neighboring resources, so that browsing appears smoother.
  _preloadNeighbors: ->
    if @collection.at(@index + 1)?
      arcs.preload @collection.at(@index + 1).get 'url'
    if @collection.at(@index - 1)?
      arcs.preload @collection.at(@index - 1).get 'url'

  # Set the resource to the next one, by index.
  next: -> @set @index + 1, trigger: true

  # Set the resource to the previous one, by index.
  prev: -> @set @index - 1, trigger: true

  # Open the resource in a new window.
  openFull: ->
    window.open @model.get('url'), '_blank', 'menubar=no'

  # Disable nav buttons if we're at either end of the range.
  checkNav: ->
    if @collection.length == @index + 1
      @$('#next-btn').addClass 'disabled'
    else
      @$('#next-btn').removeClass 'disabled'

    if @index == 0
      @$('#prev-btn').addClass 'disabled'
    else
      @$('#prev-btn').removeClass 'disabled'

  # Prompt the user to consider splitting a PDF.
  splitPrompt: ->
    return unless @model.get('mime_type') == 'application/pdf'
    new arcs.views.Modal
      title: "Split into a PDF?"
      subtitle: "We noticed you've uploaded a PDF. If you'd like, " +
        "we can split the PDF into a collection, where it can be " +
        "annotated and commented on--page by page."
      buttons:
        yes: 
          class: 'btn btn-success'
          callback: =>
            $.post arcs.baseURL + 'resources/split_pdf/' + @model.id
        no: ->

  resize: ->
    margin = if $('body').hasClass 'standalone' then 168 else 195
    well_height = $(window).height() - margin
    @$('.viewer-well').height well_height
    @$('.tab-content').height well_height - 75
    offset = @$('#resource img').css('max-height', well_height).offset()
    @$('#hotspots-wrapper').css 'left', offset.left - 56
  
  # Render the resource.
  render: ->
    # Render the resource preview
    mimeInfo = arcs.utils.mime.getInfo @model.get 'mime_type'
    switch mimeInfo.type
      when 'image' then template = 'viewer/image'
      when 'document' then template = 'viewer/document'
      when 'video' then template = 'viewer/video'
      else template = 'viewer/unknown'
    @$('#resource').html arcs.tmpl template, @model.toJSON()

    # Trigger the resourceloaded event.
    arcs.trigger 'arcs:resourceLoaded'

    # Render the resource (and collection) info tables.
    @$('#resource-details').html arcs.tmpl 'viewer/table', 
      @model.toJSON()
    if @collectionModel?
      @$('#collection-details').html arcs.tmpl 'viewer/collection_table', 
        @collectionModel.toJSON()
    @checkNav()
    @
