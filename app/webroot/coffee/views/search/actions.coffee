# actions.coffee
# --------------
arcs.views.search ?= {}
class arcs.views.search.Actions extends arcs.views.BaseActions

  initialize: ->
    @results = @collection

    @ctxMenu = new arcs.views.ContextMenu
      el: $(document)
      filter: 'img'
      options: @_getContextOptions()
      onShow: (e) ->
        $(e.currentTarget).parents('.result').addClass 'selected'
        arcs.bus.trigger 'selection'
      context: @

    arcs.keys.map @,
      'ctrl+o': @openSelected
      'ctrl+e': if arcs.user.isLoggedIn() then @editSelected else ->
      space: @previewSelected

  events:
    'dblclick img'              : 'openResource'
    'click #open-btn'           : 'openSelected'
    'click #open-colview-btn'   : 'collectionFromSelected'
    'click #collection-btn'     : 'namedCollectionFromSelected'
    'click #collection-add-btn' : 'addToCollection'
    'click #attribute-btn'      : 'editSelected'
    'click #flag-btn'           : 'flagSelected'
    'click #delete-btn'         : 'deleteSelected'
    'click #bookmark-btn'       : 'bookmarkSelected'
    'click #keyword-btn'        : 'keywordSelected'
    'click #download-btn'       : 'downloadSelected'
    'click #zipped-btn'         : 'zippedDownloadSelected'
    'click #rethumb-btn'        : 'rethumbSelected'
    'click #repreview-btn'      : 'repreviewSelected'
    'click #split-btn'          : 'splitSelected'
    'click #access-btn'         : 'setAccessForSelected'
    'click #solr-btn'           : 'indexSelected'

  # Delete the selected results, by calling Resource.destroy() on each model.
  deleteSelected: ->
    return unless @results.anySelected()
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Delete Selected'
      subtitle: "#{n} #{arcs.inflector.pluralize('resource', n)} will be " +
        "permanently deleted."
      buttons:
        delete:
          class: 'btn btn-danger'
          callback: =>
            for result in @results.selected()
              result.destroy()
            @_notify 'deleted', n
        cancel: ->

  # Open a modal (called when keyword button is clicked) and ask the user for 
  # a keyword string. Delegate further action through callbacks.
  keywordSelected: ->
    return unless @results.anySelected()
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Keyword Selected'
      subtitle: "The keyword will be applied to #{n} " +
        "#{arcs.inflector.pluralize('resource', n)}."
      backdrop: true
      inputs:
        keyword:
          label: false
          complete: arcs.complete 'keywords/complete'
          focused: true
          required: true
      buttons: 
        save:
          class: 'btn btn-success'
          validate: true
          callback: (vals) =>
            for result in @results.selected()
              @keywordResource result, vals.keyword
            @_notify 'keyworded'
        cancel: ->
  
  # Send a request for the resource to be re-thumbnailed.
  rethumbSelected: ->
    @rethumbResource(result) for result in @results.selected()

  # Send a request for the resource to be split.
  splitSelected: ->
    @splitResource(result) for result in @results.selected()

  # Opens a modal and prompts the user for information about flagging.
  flagSelected: ->
    return unless @results.anySelected()
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Flag Selected'
      subtitle: "#{n} #{arcs.inflector.pluralize('resource', n)} will be flagged."
      inputs:
        reason:
          type: 'select'
          options: _.inverse arcs.config.flags
        explain:
          type: 'textarea'
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (vals) =>
            for result in @results.selected()
              @flagResource result, vals.reason, vals.explain
            @_notify 'flagged'
        cancel: ->

  # Edit the selected results. Delegates to batchEditSelected when more than one
  # result is selected.
  editSelected: ->
    return unless @results.anySelected()
    return @batchEditSelected() if @results.numSelected() > 1
    result = @results.selected()[0]
    inputs = 
      title: value: result.get 'title'
      type: 
        type: 'select'
        options: _.keys arcs.config.types
        value: result.get 'type'

    metadata = result.get 'metadata'
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
            @editResource result, values
        cancel: ->

  # Edit the selected results in bulk. The workflow is based on the behavior of
  # the iTunes batch editor. Values that are common among all fields are
  # pre-filled and pre-checked. All checked fields (and only checked fields)
  # are updated on save, even if blank.
  batchEditSelected: ->
    # First we need to build the inputs and find any shared values.
    results = @results.selected()
    types = _.map results, (r) -> r.get 'type'
    inputs = 
      type: 
        type: 'select'
        options: _.keys arcs.config.types
        value: if _.twins(types) then results[0].get('type') else ''
        checkbox: _.twins(types) and results[0].get('type')

    for field, help of arcs.config.metadata
      continue if field in arcs.config.metadataSingular
      values = _.map results, (r) -> r.get('metadata').get(field)
      checked = _.twins(values) and values[0]
      value = if _.twins(values) and values[0] then values[0]
      inputs[field] =
        checkbox: !!checked
        value: value ? ''
        help: help

    # Make a new modal, using the columned template. Pass in our generated
    # inputs.
    new arcs.views.BatchEditModal
      title: 'Edit Info (Multiple)'
      subtitle: "The values of checked fields will be applied to all " +
        "of the selected results, even when blank."
      template: 'ui/modal_columned'
      inputs: inputs
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (values) =>
            for r in @results.selected()
              @editResource r, values
        cancel: ->

  # Create a named collection from the selected results.
  namedCollectionFromSelected: ->
    return unless @results.anySelected()

    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Create a Collection'
      subtitle: "A collection with #{n} #{arcs.inflector.pluralize('resource', n)} " +
        "will be created."
      inputs:
        title: 
          focused: true
        description:
          type: 'textarea'
      buttons:
        save:
          class: 'btn btn-success'
          callback: @collectionFromSelected
          context: @
        cancel: ->

  # Create a new collection from the selected results, and open it.
  collectionFromSelected: (vals) ->
    return unless @results.anySelected()

    collection = new arcs.models.Collection
      title: vals.title ? "Temporary Collection"
      description: vals.description ? "Results from search"
      public: false
      temporary: true
      members: _.map(@results.selected(), (r) -> r.get('id'))

    collection.save {},
      success: (newCol) ->
        window.open arcs.url('collection', newCol.id)
      error: =>
        arcs.notify 'An error occurred.', 'error'

  addToCollection: ->
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Add to existing collection'
      subtitle: "#{n} #{arcs.inflector.pluralize('resource', n)} will be added
        to the selected collection."
      inputs:
        collection:
          type: 'select'
          options: @getCollections()
      buttons:
        add:
          class: 'btn btn-success'
          callback: (vals) =>
            url = arcs.url 'collections/append/', vals.collection
            data = members: _.map(@results.selected(), (r) -> r.get('id'))
            $.postJSON url, data, =>
              @_notify 'added'
        cancel: ->

  getCollections: ->
    result = []
    $.ajax
      url: arcs.baseURL + 'collections/titles'
      async: false
      dataType: 'json'
      success: (data) ->
        result = data
    _.inverse result

  previewSelected: ->
    return unless @results.anySelected()
    # The method doubles as a toggle. If a preview is already open, we'll close
    # it and return.
    if @preview? and $('#modal').is ':visible'
      # Kill the old preview, otherwise the zombie events will come back to 
      # bite us. Preview.remove() is set up to undelegate events.
      @preview.remove()
      return @preview = null
    @preview = new arcs.views.Preview
      collection: new arcs.collections.ResultSet(@results.selected())

  # Download selected files.
  downloadSelected: ->
    @downloadResource(result) for result in @results.selected()

  # Request a download link for a zipfile of selected resources.
  zippedDownloadSelected: ->
    unless @results.numSelected() > 1
      return arcs.notify 'To download resources zipped, select at least 2.',
    data = resources: _.pluck @results.selected(), 'id'
    $.postJSON arcs.baseURL + 'resources/zipped', data, (response) =>
      if response.url?
        iframe = @make 'iframe', 
          style: 'display:none'
        $('body').append(iframe)
        iframe.src = response.url
    arcs.notify "Hold tight. We're building your zipfile. " +
      "Your download will start in a moment", 'success'

  # Call bookmarkResource on all selected results.
  bookmarkSelected: ->
    @bookmarkResource(result) for result in @results.selected()
    @_notify 'bookmarked' if @results.anySelected()

  # Open all selected results through `openResource`
  openSelected: -> 
    @openResource(result) for result in @results.selected()
    @_notify 'opened' if @results.anySelected()

  setAccessForSelected: ->
    n = @results.numSelected()
    settings = _.map @results.selected(), (r) -> r.get 'public'
    if not _.twins(settings)
      value = ''
    else
      value = if settings[0] then 'Public' else 'Private'
    new arcs.views.Modal
      title: 'Set Access'
      subtitle: "<b>Private</b> resources may only be viewed by ARCS users. " +
        "<b>Public</b> resources may be viewed by the general public."
      inputs:
        access:
          type: 'select'
          options: ['', 'Public', 'Private']
          value: value
      buttons:
        save: 
          class: 'btn btn-success'
          callback: (vals) =>
            return unless vals.access
            for result in @results.selected()
              result.set 'public', vals.access == 'Public'
              result.save()
        cancel: ->

  indexSelected: ->
    @indexResource(result) for result in @results.selected()

  repreviewSelected: ->
    @repreviewResource(result) for result in @results.selected()

  # Displays a success notification given a past-tense verb.
  # We have a lot of "12 resources were tagged"-style notifications. This 
  # method simplifies those calls to just a verb.
  _notify: (verb='affected', n) ->
    n ?= @results.numSelected()
    msg = "#{n} #{arcs.inflector.pluralize('resource', n)} " +
      "#{arcs.inflector.conjugate('was', n)} #{verb}."
    arcs.notify msg, 'success'

  # Resolves a jQuery Event or a DOM el to a Resource model, if possible.
  _modelFromRef: (ref) ->
    if ref instanceof arcs.models.Resource
      return ref 
    if ref instanceof jQuery.Event
      ref.preventDefault()
      ref = $(ref.currentTarget).parents '.result'
    id = $(ref).data 'id'
    @results.get id

  # Return context menu options based on the current user's state.
  _getContextOptions: ->
    public =
      'Open'     : 'openSelected'
      'Preview'  : 'previewSelected'
      'Download' : 'downloadSelected'
    
    restricted =
      'Edit...' : 'editSelected'
      'Flag...' : 'flagSelected'

    admin =
      'Delete...' : 'deleteSelected'

    return _.extend(public, restricted, admin) if arcs.user.isAdmin()
    return _.extend(public, restricted) if arcs.user.isLoggedIn()
    public
