# actions.coffee
# -------------
class arcs.views.ViewerActions extends arcs.views.BaseActions

  initialize: ->
    @viewer = @options.viewer
    arcs.bus.on 'indexChange', =>
      @updateNav arguments[0]
      @checkNav()
    @onNavKeyup = _.debounce @setNav, 1000
    arcs.keys.map @,
      'ctrl+e': if arcs.user.isLoggedIn() then @edit else ->
      '-': @zoomOut
      '+': @zoomIn
      '0': -> @zoomTo 1
      p: @onNavClick
    if screenfull
      screenfull.onchange = _.bind(@onFullScreenChange, @)

  events:
    'click .page-nav input'      : 'onNavClick'
    'keyup .page-nav input'      : 'onNavKeyup'
    'keydown .page-nav input'    : 'onNavKeydown'
    'keydown .collection-search' : 'search'
    'click #thumbs-btn'          : 'openInSearch'
    'click #mini-next-btn'       : 'next'
    'click #mini-prev-btn'       : 'prev'
    'click #edit-btn'            : 'edit'
    'click #flag-btn'            : 'flag'
    'click #full-screen-btn'     : 'fullScreen'
    'click #delete-btn'          : 'delete'
    'click #delete-col-btn'      : 'deleteCollection'
    'click #split-btn'           : 'split'
    'click #rethumb-btn'         : 'rethumb'
    'click #download-btn'        : 'download'
    'click #annotate-btn'        : 'annotate'
    'click #zoom-in-btn'         : 'zoomIn'
    'click #zoom-out-btn'        : 'zoomOut'

  onNavClick: ->
    @$('.page-nav input').select()

  onNavKeyup: (e) ->
    @setNav() unless e.which == 13

  onNavKeydown: (e) ->
    @setNav() if e.which == 13

  setNav: ->
    unless @viewer.set @$('.page-nav input').val() - 1, {trigger: true}
      unless @$('.page-nav input').val() == ''
        @$('.page-nav input').val @viewer.index + 1

  updateNav: (index) ->
    @$('.page-nav input').val index + 1

  checkNav: ->
    if @viewer.collection.length == @viewer.index + 1
      @$('#mini-next-btn').addClass 'disabled'
    else
      @$('#mini-next-btn').removeClass 'disabled'

    if @viewer.index == 0
      @$('#mini-prev-btn').addClass 'disabled'
    else
      @$('#mini-prev-btn').removeClass 'disabled'

  search: (e) ->
    return unless e.which == 13
    query = @$('.collection-search').val()
    facets = "collection:'#{arcs.collectionModel.get 'title'}' text:'#{query}'"
    location.href = arcs.baseURL + "search/" +
      encodeURIComponent facets

  openInSearch: ->
    location.href = arcs.baseURL + "search/" +
      encodeURIComponent "collection:'#{arcs.collectionModel.get 'title'}'"

  next: ->
    @viewer.next()

  prev: ->
    @viewer.prev()

  flag: ->
    new arcs.views.Modal
      title: 'Flag'
      inputs:
        reason:
          type: 'select'
          options:
            'Incorrect attributes' : 'incorrect'
            'Spam'                 : 'spam'
            'Duplicate'            : 'duplicate'
            'Other'                : 'other'
        explain:
          type: 'textarea'
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (vals) =>
            @flagResource @viewer.model, vals.reason, vals.explain
        cancel: ->

  edit: ->
    inputs = 
      title: value: @viewer.model.get 'title'
      type: 
        type: 'select'
        options: _.keys arcs.config.types
        value: @viewer.model.get 'type'

    metadata = @viewer.model.get 'metadata'
    fields = arcs.config.metadata
    for field, help of fields
      inputs[field] = 
        value: metadata.get(field) ? ''
        help: help

    new arcs.views.Modal
      title: 'Edit Info'
      subtitle: ''
      template: 'ui/modal_columned'
      inputs: inputs
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (values) =>
            @editResource @viewer.model, values
        cancel: ->

  # Delete the current resource.
  delete: ->
    arcs.confirm "Are you sure you want to delete this resource?",
      "<b>#{@viewer.model.get 'title'}</b> will be permanently deleted.", =>
        @viewer.model.destroy()

  deleteCollection: ->
    arcs.confirm "Are you sure you want to delete this collection?",
      "<p>Collection <b>#{@viewer.collectionModel.get('title')}</b> will be " +
      "deleted. <p><b>N.B.</b> Resources within the collection will not be " +
      "deleted. They may still be accessed from other collections to which they " +
      "belong.", =>
        arcs.loader.show()
        $.ajax
          url: arcs.url 'collections', 'delete', @viewer.collectionModel.id
          type: 'DELETE'
          success: ->
            location.href = arcs.baseURL

  rethumb: ->
    @rethumbResource @viewer.model

  split: ->
    @splitResource @viewer.model

  download: ->
    @downloadResource @viewer.model

  annotate: ->
    arcs.bus.trigger 'annotate'

  fullScreen: ->
    if screenfull
      screenfull.toggle()
    else
      arcs.notify "Your browser does not support the full-screen API." +
        "You'll need to open fullscreen manually, or upgrade your browser."

  onFullScreenChange: ->
    if screenfull.isFullscreen
      @$('#full-screen-btn i').removeClass('icon-resize-full').addClass('icon-resize-small')
    else
      @$('#full-screen-btn i').addClass('icon-resize-full').removeClass('icon-resize-small')
    setTimeout(->
      arcs.bus.trigger 'resourceResize'
    , 1000)

  zoomTo: (level) ->
    $('#resource img').data 'zoom', level
    @viewer.resize()
    @_checkZoom()
    arcs.bus.trigger 'resourceReloaded'

  zoomIn: ->
    current = $('#resource img').data 'zoom'
    if current < 2
      $('#resource img').data 'zoom', current + 0.25
    @viewer.resize()
    @_checkZoom()
    arcs.bus.trigger 'resourceReloaded'

  zoomOut: ->
    current = $('#resource img').data 'zoom'
    if current > 1
      $('#resource img').data 'zoom', current - 0.25
    @viewer.resize()
    @_checkZoom()
    arcs.bus.trigger 'resourceReloaded'

  _checkZoom: ->
    zoom = $('#resource img').data 'zoom'
    if zoom == 1
      @$('#zoom-out-btn').addClass 'disabled'
    if zoom == 2
      @$('#zoom-in-btn').addClass 'disabled'
    if zoom > 1
      @$('#zoom-out-btn').removeClass 'disabled'
    if zoom < 2
      @$('#zoom-in-btn').removeClass 'disabled'
