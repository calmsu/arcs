# annotation.coffee
# -----------------
class arcs.views.Annotation extends Backbone.View

  initialize: ->
    @collection = new arcs.collections.AnnotationList
    @collection.on 'add sync reset remove', @render, @

    arcs.bus.on 'resourceLoaded', @onLoad, @
    arcs.bus.on 'resourceReloaded', @render, @
    arcs.bus.on 'resourceResize', @render, @
    arcs.bus.on 'indexChange', @clear, @
    arcs.bus.on 'annotate', @toggleState, @

    @visible = true
    @active = false

    $('#annotation-vis-btn').on 'click', => @toggleVisibility()
    arcs.keys.map @, a: @toggleVisibility

  events:
    'click #annotate-new-btn'  : 'newAnnotation'
    'click #annotate-done-btn' : 'exit'
    'mouseenter .annotation'   : 'onSummaryMouseenter'
    'mouseleave .annotation'   : 'onSummaryMouseleave'
    'mouseenter .hotspot'      : 'onBoxMouseenter'
    'hover .annotation a'      : 'onSummaryMouseenter'
    'click .remove-btn'        : 'removeAnnotation'

  onLoad: ->
    @img = $('#resource img')
    @setupSelection() if @active
    @collection.fetch()

  toggleState: ->
    if @active then @exit() else @enter()

  enter: ->
    @$('.annotate-controls').show()
    $('#annotate-btn').addClass 'disabled'
    $('.hotspot i').show()
    $('#wrapping').css 'cursor', 'default'
    @setupSelection()
    @active = true

  exit: ->
    @$('.annotate-controls').hide()
    $('#annotate-btn').removeClass 'disabled'
    $('.hotspot i').hide()
    @removeIas()
    @active = false

  onSummaryMouseenter: (e) ->
    if e.target.tagName == 'A'
      $li = $(e.target).parent()
    else
      $li = $(e.target)
    id = $li.data 'id'
    $('.hotspot').removeClass 'active'
    $(".hotspot[data-id='#{id}']").addClass 'active'

  onSummaryMouseleave: ->
    $('.hotspot').removeClass 'active'

  onBoxMouseenter: (e) ->
    $el = $(e.target)
    anno = @collection.get $el.data 'id'
    $el.popover
      title: arcs.tmpl('viewer/popover_title', {type: anno.getType()})
      content: arcs.tmpl 'viewer/popover', anno.toJSON()
      placement: @_placePopover($el)
    $el.popover 'show'

  # When initializing a popover, choose the side with the most room.
  _placePopover: ($el) ->
    [maxWidth, maxHeight] = [$(window).width(), $(window).height()]
    offsets = 
      left: $el.offset().left
      top: $el.offset().top
      right: maxWidth - ($el.offset().left + $el.width())
      bottom: maxHeight - ($el.offset().top + $el.height())
    [choice, best] = ['right', offsets.right]
    (best = offsets[(choice = k)] if offsets[k] > best) for k of offsets 
    choice = 'top' if offsets.bottom < 0
    choice = 'bottom' if offsets.top < 0
    choice

  setupSelection: (coords=null) ->
    @removeIas()
    @ias = @img.imgAreaSelect
      instance: true
      handles: true
      fadeSpeed: 250
      parent: @$('.viewer-well')
      onSelectEnd: (img, sel) =>
        return arcs.needsLogin() unless arcs.user.get 'loggedIn'
        @openAnnotator()

  # Help ImgAreaSelect clean up after itself.
  removeIas: ->
    @img.removeData 'imgAreaSelect'
    if @ias?
      @ias.remove()
      @ias = null

  toggleVisibility: ->
    @visible = !@visible
    msg = "Annotations are #{if @visible then 'visible' else 'hidden'}"
    $btn = $('#annotation-vis-btn')
    $btn.toggleClass('opaque').attr('data-original-title', msg).tooltip 'show'
    _.delay (=> $btn.tooltip 'hide'), 1000
    return @collection.fetch() if @visible
    $('#hotspots-wrapper').html ''

  removeAnnotation: (e) ->
    e.stopPropagation() # Don't trigger the parent anchor.
    $hotspot = $(e.target).parent()
    $hotspot.popover 'hide'
    anno = @collection.get $hotspot.data 'id'
    return unless anno
    arcs.confirm 'Are you sure?', 
      "This <b>#{anno.getType().toLowerCase()}</b> will be deleted.", =>
        anno.destroy()
    false

  newAnnotation: ->
    [width, height] = [@img.width(), @img.height()]
    xMid = Math.floor width / 2
    yMid = Math.floor height / 2
    @ias.setOptions show: true
    @ias.setSelection(xMid - 50, yMid - 50, xMid + 50, yMid + 50)
    @ias.update()

  openAnnotator: ->
    return if @annotator?.isOpen()
    @annotator = new arcs.views.Modal
      title: 'New Annotation'
      subtitle: arcs.tmpl 'viewer/annotator'
      backdrop: false
      class: 'annotator'
      buttons:
        save: 
          class: 'btn btn-success'
          callback: =>
            data =
              relation   : @annotator.$('.result.selected img').data 'id'
              transcript : @annotator.$('textarea#transcript').val()
              url        : @annotator.$('input#url').val()
            return unless _.any data
            @create data
            @ias.cancelSelection() 
            delete @annotator
        cancel: => 
          @ias.cancelSelection() 
          delete @annotator

    # Search result selection.
    @annotator.$el.on 'click', '.result img', ->
      $('.result').removeClass 'selected'
      $(@).parents('.result').addClass 'selected'

    # Remove http:// from the input.
    @annotator.$('input#url').keyup ->
      val = $(@).val()
      $(@).val(val.substring(7)) if val.substring(0, 7) == 'http://' 

    @search = new arcs.utils.Search
      container: $('.mini-search')
      success: =>
        $('.mini-search-results').html arcs.tmpl 'search/grid', 
          results: @search.results.toJSON()

  create: (data) ->
    anno = new arcs.models.Annotation
      resource_id: arcs.resource.id
    # Figure out what we're saving. Precedence by tab order.
    if data.relation
      anno.set 'relation', data.relation
    else if data.transcript
      anno.set 'transcript', data.transcript
    else
      anno.set 'url', 'http://' + data.url
    if @ias?
      anno.setScaled @ias.getSelection(), @img.height(), @img.width()
    anno.save()
    anno.set 'id', _.uniqueId()
    @collection.add(anno)

  # Wipe out any annotations.
  clear: ->
    $('#annotations-wrapper').html ''
    $('#hotspots-wrapper').html ''
    # Remove any stray popovers.
    $('.popover').remove()

  # Render annotations and hotspots.
  render: ->
    @clear()
    annos = 
      annotations: @collection.map (m) =>
        if rid = m.get 'relation'
          # Find our relation's Resource model (If the anno is new, it's in the 
          # search results col. If the old, the server gave it to us.)
          m.set 'relation', 
            (@search?.results.get(rid) || @collection.relations?.get(rid)).toJSON()
        _.extend m.toJSON(), m.scaleTo(@img.height(), @img.width())
      offset: $('#resource img').offset().left - $('#resource').offset().left
    $('#annotations-wrapper').html arcs.tmpl 'viewer/annotations', annos
    if @visible
      $('#hotspots-wrapper').html arcs.tmpl 'viewer/hotspots', annos
    if @active
      $('.hotspot i').show()
    @
