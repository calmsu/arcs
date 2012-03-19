# search_actions.coffee
# ---------------------
class arcs.views.SearchActions extends Backbone.View

  initialize: ->
    @results = @collection

    # <ctrl>-o to open selected
    arcs.keys.add 'o', true, @openSelected, @

  events:
    'dblclick img'           : 'openResult'
    'click #open-btn'        : 'openSelected'
    'click #open-colview-btn': 'collectionFromSelected'
    'click #collection-btn'  : 'namedCollectionFromSelected'
    'click #attribute-btn'   : 'editSelected'
    'click #flag-btn'        : 'flagSelected'
    'click #delete-btn'      : 'deleteSelected'
    'click #bookmark-btn'    : 'bookmarkSelected'
    'click #keyword-btn'     : 'keywordSelected'

  # Open the resource view for a result.
  openResult: (result) ->
    # This method can be bound to a click event, so we'll resolve the 
    # result value first. 
    result = @_modelFromRef(result)
    window.open arcs.baseURL + 'resource/' + result.id

  # Creates a new Keyword
  keywordResult: (result, string) -> 
    keyword = new arcs.models.Keyword
      resource_id: result.id
      keyword: string
    keyword.save()

  # Creates a new Flag
  flagResult: (result, reason, explanation) ->
    flag = new arcs.models.Flag
      resource_id: result.id
      reason: reason
      explanation: explanation
    flag.save()

  # Create a new Bookmark, given a result element and optionally a note 
  # string.
  bookmarkResult: (result, note) ->
    bkmk = new arcs.models.Bookmark
      resource_id: result.id
      description: note 
    bkmk.save()

  deleteSelected: ->
    return arcs.notify "Select a result" unless @results.anySelected()
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Delete Selected'
      subtitle: "#{n} #{arcs.pluralize('resource', n)} will be " +
        "permanently deleted."
      buttons:
        delete:
          class: 'btn danger'
          callback: =>
            for result in @results.selected()
              result.destroy()
            @_notify 'deleted', n
        cancel: ->

  # Open a modal (called when keyword button is clicked) and ask the user for 
  # a keyword string. Delegate further action through callbacks.
  keywordSelected: ->
    return arcs.notify "Select a result" unless @results.anySelected()
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Keyword Selected'
      subtitle: "The keyword will be applied to #{n} " +
        "#{arcs.pluralize('resource', n)}."
      backdrop: true
      inputs:
        keyword:
          label: false
          complete: arcs.utils.complete.keyword
          focused: true
      buttons: 
        save:
          class: 'btn success'
          callback: (vals) =>
            for result in @results.selected()
              @keywordResult result, vals.keyword
            @_notify 'keyworded'
        cancel: ->

  # Opens a modal and prompts the user for information about flagging.
  flagSelected: ->
    return unless @results.anySelected()
    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Flag Selected'
      subtitle: "#{n} #{arcs.pluralize('resource', n)} will be flagged."
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
          class: 'btn success'
          callback: (vals) =>
            for result in @results.selected()
              @flagResult result, vals.reason, vals.explain
            @_notify 'flagged'
        cancel: ->

  # Edit the selected results. Delegates to batchEditSelected when more than one
  # result is selected.
  editSelected: ->
    return arcs.notify "Select a result" unless @results.anySelected()
    return @batchEditSelected() if @results.numSelected() > 1
    result = @results.selected()[0]
    inputs = {}
    for field in result.MODIFIABLE
      inputs[field] = value: result.get(field) ? ''

    new arcs.views.Modal
      title: 'Edit Attributes'
      subtitle: ''
      template: 'ui/modal_columned'
      inputs: inputs
      buttons:
        save: 
          class: 'btn success'
          callback: ->
        cancel: ->

  # Edit the selected results in bulk. The workflow is based on the behavior of
  # the iTunes batch editor. Values that are unique among all fields are
  # pre-filled and pre-checked. All checked fields (and only checked fields)
  # are updated on save, even if blank.
  batchEditSelected: ->
    inputs = {}
    results = @results.selected()
    batchFields = _.difference results[0].MODIFIABLE, results[0].SINGULAR
    for field in batchFields
      values = (r.get(field) for r in results)
      [checked, value] = [false, '']
      if _.unique(values).length == 1 and values[0] != undefined
        checked = true
        value = values[0]
      inputs[field] =
        checkbox: checked
        value: value ? ''

    new arcs.views.Modal
      title: 'Edit Attributes (Multiple)'
      subtitle: "The values of checked fields will be applied to all " +
        "of the selected results, even when blank."
      template: 'ui/modal_columned'
      inputs: inputs
      buttons:
        save: 
          class: 'btn success'
          callback: ->
        cancel: ->

  # Create a named collection from the selected results.
  namedCollectionFromSelected: ->
    return arcs.notify "Select a result" unless @results.anySelected()

    n = @results.numSelected()
    new arcs.views.Modal
      title: 'Create a Collection'
      subtitle: "A collection with #{n} #{arcs.pluralize('resource', n)} " +
        "will be created."
      inputs:
        title: 
          focused: true
        description:
          type: 'textarea'
      buttons:
        save:
          class: 'btn success'
          callback: @collectionFromSelected
          context: @
        cancel: ->

  # Create a new collection from the selected results, and open it.
  collectionFromSelected: (vals) ->
    return arcs.notify "Select a result" unless @results.anySelected()

    collection = new arcs.models.Collection
      title: vals.title ? "Temporary Collection"
      description: vals.description ? "Results from search"
      public: false
      temporary: true
      members: _.map(@results.selected(), (r) -> r.get('id'))

    collection.save {},
      success: (newCol) ->
        window.open(arcs.baseURL + 'collection/' + newCol.id)
      error: =>
        arcs.notify 'An error occurred.', 'error'

  # Call bookmarkResult on all selected results.
  bookmarkSelected: ->
    @bookjmarkResult(result) for result in @results.selected()
    @_notify 'bookmarked'

  # Open all selected results through openResult
  openSelected: -> 
    @openResult(result) for result in @results.selected()
    @_notify 'opened'

  # Displays a success notification given a past-tense verb.
  _notify: (verb='affected', n) ->
    n ?= @results.numSelected()
    msg = "#{n} #{arcs.pluralize('resource', n)} #{arcs.conjugate('was', n)} #{verb}."
    arcs.notify msg, 'success'

  # Resolves a jQuery Event or a DOM el to a Resource model, if possible.
  _modelFromRef: (ref) ->
    if ref instanceof arcs.models.Resource
      return ref 
    if ref instanceof jQuery.Event
      ref.preventDefault()
      ref = $(ref.currentTarget).parent()
    id = $(ref).find('img').attr 'data-id'
    @results.get id