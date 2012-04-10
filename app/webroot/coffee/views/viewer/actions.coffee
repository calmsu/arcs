# actions.coffee
# -------------
class arcs.views.ViewerActions extends arcs.views.BaseActions

  initialize: ->
    @viewer = @options.viewer
    arcs.on 'arcs:indexChange', =>
      @updateNav arguments[0]
      @checkNav()
    @onNavKeyup = _.debounce @setNav, 1000

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
    'click #split-btn'           : 'split'
    'click #rethumb-btn'         : 'rethumb'
    'click #download-btn'        : 'download'

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
    metadata = arcs.resource.get 'metadata'
    fields = arcs.resource.MODIFIABLE.sort()
    inputs = {}
    for field in fields
      inputs[field] = value: metadata[field] ? ''

    new arcs.views.Modal
      title: 'Edit Info'
      template: 'ui/modal_columned'
      inputs: inputs
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (values) =>
            return if _.isEqual metadata, values
            @editResource @viewer.model, values
        cancel: ->

  # Delete the current resource.
  delete: ->
    @viewer.model.destroy()

  rethumb: ->
    @rethumbResource @viewer.model

  split: ->
    @splitResource @viewer.model

  download: ->
    @downloadResource @viewer.model

  # Try to use the full-screen API to toggle the browser's full-screen mode.
  # This is Chrome 15+ and Firefox 9.0+.
  fullScreen: ->
    # Just store the state in the icon class. Host object fullScreen attributes
    # are too non-standard atm.
    if @$('#full-screen-btn i').hasClass 'icon-resize-small'
      if document.cancelFullScreen
        document.cancelFullScreen()
      else if document.mozCancelFullScreen
        document.mozCancelFullScreen()
      else if document.webkitCancelFullScreen
        document.webkitCancelFullScreen()
    else
      docEl = document.documentElement
      if docEl.requestFullScreen
        docEl.requestFullScreen()
      else if docEl.mozRequestFullScreen
        docEl.mozRequestFullScreen()
      else if docEl.webkitRequestFullScreen
        docEl.webkitRequestFullScreen()
      else
        return arcs.notify "We're unable to open screen for you. You can either " + 
          "open it manually, or install the latest version of either Google " +
          "Chrome or Mozilla Firefox."
    @$('#full-screen-btn i').toggleClass('icon-resize-full')
      .toggleClass 'icon-resize-small'
