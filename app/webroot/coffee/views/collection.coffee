# collection.coffee
# -----------------
class arcs.views.Collection extends Backbone.View

  initialize: ->
    # Set the resource on the `indexchange` event.
    # This may be triggered by the router or the Carousel view.
    arcs.on 'arcs:indexchange', @set, @
    # Spy on the indexchange event--for debugging only.
    arcs.on 'arcs:indexchange', ->
      arcs.log 'arcs:indexchange', arguments

    # Add our hotkeys
    arcs.keys.add 'left', false, @prev, @
    arcs.keys.add 'right', false, @next, @

    # Init sub-views
    @discussion = new arcs.views.Discussion
      el: $('#discussion')
    @tags = new arcs.views.Tag
      el: $('#information')
    @hotspots = new arcs.views.Hotspot
      el: $('#resource')
    @toolbar = new arcs.views.Toolbar
      el: $('#toolbar')
    @carousel = new arcs.views.Carousel
      el: $('#carousel')
      collection: @collection
      index: @index ? 0

    # Start the router and Backbone.history
    @router = new arcs.routers.Resource
    Backbone.history.start
      pushState: true
      root: arcs.baseURL + 
        if @collection.length then 'collection/' else 'resource/'

    # Special logic for a resource's first request post-upload.
    if @model.get 'first_req'
      if @model.get 'mime_type' == 'application/pdf'
        @splitPrompt()

    @index ?= 0

  events:
    'dblclick img'       : 'open'
    'click #next-button' : 'next'
    'click #prev-button' : 'prev'

  # Set the resource, given an identifier.
  #
  #  identifier - resource id or index within the collection
  #
  #  options -
  #    trigger    - if true, trigger an indexchange event.
  #    noSet      - used internally when triggering the indexchange event
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
    [@model, @index] = [model, index]

    # Trigger indexchange if the trigger opt was set.
    arcs.trigger('arcs:indexchange', index, noSet: true) if options.trigger
    @render() unless options.noRender
    # Update the location
    route = "#{arcs.collectionData.id ? @model.id}/#{@index + 1}"
    @router.navigate(route) unless options.noNavigate
    # Return true if we were able to set the model and index.
    return true

  # Set the resource to the next one, by index.
  next: -> @set @index + 1, trigger: true

  # Set the resource to the previous one, by index.
  prev: -> @set @index - 1, trigger: true

  # Open the resource in a new window.
  open: ->
    window.open @model.get('url'), '_blank', 'menubar=no'

  # Disable nav buttons if we're at either end of the range.
  checkNav: ->
    if @collection.length == @index + 1
      @$('#next-button').addClass 'disabled'
    else
      @$('#next-button').removeClass 'disabled'

    if @index == 0
      @$('#prev-button').addClass 'disabled'
    else
      @$('#prev-button').removeClass 'disabled'

  # Prompt the user to consider splitting a PDF.
  splitPrompt: ->
    if @model.get('mime_type') == 'application/pdf'
      new arcs.views.Modal
        title: "Split into a PDF?"
        subtitle: "We noticed you've uploaded a PDF. If you'd like, " +
          "we can split the PDF into a collection, where it can be " +
          "annotated and commented on--page by page."
        buttons:
          yes: 
            class: 'btn-success'
            callback: =>
              $.get arcs.baseURL + 'resources/pdfSplit/' + @model.id
          no:
            text: 'No, leave it alone'
  
  # Render the resource.
  render: ->
    # Render the resource preview
    mimeInfo = arcs.utils.mime.getInfo @model.get 'mime_type'
    switch mimeInfo.type
      when 'image' then template = 'resource/image'
      when 'document' then template = 'resource/document'
      when 'video' then template = 'resource/video'
      else template = 'resource/unknown'
    @$('#resource').html arcs.tmpl template, @model.toJSON()

    # Trigger the resourceloaded event.
    arcs.trigger 'arcs:resourceloaded'

    # Render the resource (and collection) info tables.
    @$('#resource-details').html arcs.tmpl 'resource/table', 
      @model.toJSON()
    if _.has(arcs, 'collectionData')
      @$('#collection-details').html arcs.tmpl 'resource/collection_table', 
        arcs.collectionData
    @checkNav()
    @
