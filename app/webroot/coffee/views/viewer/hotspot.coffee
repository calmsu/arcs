class arcs.views.Hotspot extends Backbone.View

  initialize: ->
    @collection = new arcs.collections.HotspotMap

    # Create a throttled render function that will be triggered
    # on window resizes.
    @reRender = _.throttle @render, 50
    arcs.bind 'arcs:resourceResize', @reRender, @

    # Bind the setup to the resourceLoaded event,
    # which is triggered directly after the resource
    # is rendered (and after resourceChange).
    arcs.bind 'arcs:resourceLoaded', @setup, @

    @collection.bind 'add remove reset', @render, @

  # Cache the image selector, call @startImgAreaSelect
  setup: ->
    @img = @$el.find('img')
    if @img?
      @startImgAreaSelect()
      @collection.fetch()

  # Fire up a new ImgAreaSelect instance.
  startImgAreaSelect: (coords=null) ->
    @img.imgAreaSelect
      handles: true
      onSelectEnd: (img, sel) =>
        # Store the hotspot data during the modal
        # intermission.
        @currentHotspot = 
          img: img
          sel: sel
        @openModal()

  # Open a modal dialog to save the selection.
  openModal: ->
    modal = new arcs.views.Modal
      title: 'Annotation Sidebar'
      backdrop: false
      class: 'hotspot-modal'
      inputs:
        type:
          type: 'select'
          options: 
            'Photo' : 'photo'
            'Sketch': 'sketch' 
        title: true
        caption:
          type: 'textarea'
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (vals) =>
            vals.resource = $('.result.selected img').data('id')
            @saveHotspot vals
            @img.imgAreaSelect hide:true
        cancel: => 
          @img.imgAreaSelect hide:true

    modal.$('.modal-body').append arcs.tmpl 'viewer/hotspot_modal'
    modal.$('.img-wrapper img').live 'click', ->
      $('.result').removeClass 'selected'
      $(@).parents('.result').addClass 'selected'

    # Remove http:// from the input. (It's assumed.)
    modal.$('input#url').keyup ->
      val = $(@).val()
      if val.substring(0, 7) == 'http://' 
        $(@).val(val.substring(7))

    # Start the search with a success callback.
    $results = $('#hotspot-search-results')
    search = new arcs.utils.Search
      container: $('#hotspot-search')
      success: =>
        $results.html arcs.tmpl 'search/grid', results: search.results.toJSON()

  # Save the hotspot after the ImgAreaSelect callback.
  saveHotspot: (data) ->
    scaled = @_scaleDown(@currentHotspot.sel)
    if data.url
      data.link = 'http://' + data.url
    else if data.resource
      data.link = 'arcs://' + data.resource
    else
      data.link = null
    hotspot = new arcs.models.Hotspot
      resource_id: arcs.resource.id
      type: data.type
      caption: data.caption
      title: data.title
      link: data.link
      x1: scaled.x1
      x2: scaled.x2
      y1: scaled.y1
      y2: scaled.y2
    hotspot.save()
    arcs.trigger 'arcs:resourceResize'
    @collection.add(hotspot)

  # We don't store the size the image is displayed at when hotspotted, so 
  # actual coordinates are useless. Scale x,y to the range (0,1).
  _scaleDown: (cds) ->
    cds.x1 /= @img.width()
    cds.y1 /= @img.height()
    cds.x2 /= @img.width()
    cds.y2 /= @img.height()
    cds

  # Scale coordinates in the range (0, 1) to the dimensions of the image.
  _scaleUp: (cds) ->
    cds.x1 *= @img.width()
    cds.y1 *= @img.height()
    cds.x2 *= @img.width()
    cds.y2 *= @img.height()
    cds

  # Render the hotspots over the image and the corresponding annotation
  # details in the sidebar.
  render: ->
    $hotspots = $('#hotspots-wrapper')
    $annotations = $('#annotations-wrapper')
    $hotspots.html ''
    $annotations.html ''

    # Calculate the CSS given the scaled down coordinates.
    json = hotspots: []
    for m in @collection.models
      data = @_scaleUp m.toJSON()
      data.left = data.x1
      data.width = data.x2 - data.x1
      data.top = data.y1
      data.height = data.y2 - data.y1
      # Resolve the link.
      if data.link? and data.link.substring(0, 7) == 'arcs://'
        data.link = arcs.baseURL + 'resource/' + data.link.substring(7)
      json.hotspots.push data

    # Render the templates.
    $hotspots.html arcs.tmpl 'viewer/hotspots', json
    $annotations.html arcs.tmpl 'viewer/annotations', json
    @
