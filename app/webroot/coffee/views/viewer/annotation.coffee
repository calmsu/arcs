# annotation.coffee
# -----------------
class arcs.views.Annotation extends Backbone.View

  initialize: ->
    @collection = new arcs.collections.AnnotationList
    @collection.on 'add sync reset remove', @render, @
    arcs.bus.on 'resourceLoaded', @onLoad, @
    arcs.bus.on 'resourceResize', @render, @
    arcs.bus.on 'indexChange', @clear, @

  events:
    'mouseenter .annotation' : 'annoMouseenter'
    'mouseleave .annotation' : 'annoMouseleave'
    'mouseenter .hotspot'    : 'hotMouseenter'
    'hover .annotation a'    : 'annoMouseenter'
    'click .remove-btn'      : 'removeAnnotation'

  onLoad: ->
    @img = $('#resource img')
    @setupSelection()
    @collection.fetch()

  annoMouseenter: (e) ->
    if e.target.tagName == 'A'
      $li = $(e.target).parent()
    else
      $li = $(e.target)
    id = $li.data 'id'
    $('.hotspot').removeClass 'active'
    $(".hotspot[data-id='#{id}']").addClass 'active'

  annoMouseleave: ->
    $('.hotspot').removeClass 'active'

  hotMouseenter: (e) ->
    $el = $(e.target)
    anno = @collection.get $el.data 'id'
    $el.popover
      title: anno.getType()
      content: arcs.tmpl 'viewer/popover', anno.toJSON()
    $el.popover 'show'

  setupSelection: (coords=null) ->
    @ias.remove() if @ias?
    @ias = @img.imgAreaSelect
      instance: true
      handles: true
      onSelectEnd: (img, sel) =>
        return arcs.needsLogin() unless arcs.user.get 'loggedIn'
        @openAnnotator()

  removeAnnotation: (e) ->
    $hotspot = $(e.target).parent()
    $hotspot.popover 'hide'
    anno = @collection.get $hotspot.data 'id'
    return unless anno
    arcs.confirm 'Are you sure?', 
      "This #{anno.getType()} will be deleted.", =>
        anno.destroy()

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

  # Render annotations and hotspots.
  render: ->
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
    $('#hotspots-wrapper').html arcs.tmpl 'viewer/hotspots', annos
    @
