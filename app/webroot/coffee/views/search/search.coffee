# search.coffee
# -------------
# Search View. Select and perform bulk actions on search results.
arcs.views.search ?= {}
class arcs.views.search.Search extends Backbone.View

  options:
    sort: 'title'
    sortDir: 'asc'
    grid: true
    url: arcs.baseURL + 'search/'

  ### Initialize and define events ###

  initialize: ->
    @setupSelect()
    @setupSearch()

    # Init our sub-view for actions.
    @actions = new arcs.views.search.Actions
      el: @$el
      collection: @search.results

    # Setup our Router
    @router = new arcs.routers.Search
      search: @search

    # Start Backbone.history
    Backbone.history.start
      pushState: true
      root: @options.url

    # Search unless the Router already delegated it.
    unless @router.searched 
      @search.run null,
        order: @options.sort
        direction: @options.sortDir

    # Set up some event bindings.
    @search.results.on 'change remove', @render, @
    arcs.bus.on 'selection', @afterSelection, @

    # <ctrl>-a to select all
    arcs.keys.map @,
      'ctrl+a': @selectAll
      '?': @showHotkeys
      t: @scrollTop

    @setupHelp()

  events:
    'click img'                : 'toggle'
    'click .result'            : 'maybeUnselectAll'
    'click #search-results'    : 'maybeUnselectAll'
    'click #grid-btn'          : 'toggleView'
    'click #list-btn'          : 'toggleView'
    'click #top-btn'           : 'scrollTop'
    'click .sort-btn'          : 'setSort'
    'click .dir-btn'           : 'setSortDir'
    'click .search-page-btn'   : 'setPage'

  ### More involved setups run by the initialize method ###

  # Set up drag-to-select, using jQuery.ui.selectable
  setupSelect: ->
    @$el.find('#search-results').selectable
      # Little bit of drag tolerance
      distance: 20
      # Images are the selectables
      filter: '.img-wrapper img'
      # Make jQuery UI call our selection methods.
      selecting: (e, ui) => 
        $(ui.selecting).parents('.result').addClass('selected')
        @afterSelection()
      selected: (e, ui) =>
        $(ui.selected).parents('.result').addClass('selected')
        @afterSelection()
      unselecting: (e, ui) =>
        $(ui.unselecting).parents('.result').removeClass('selected')
        @afterSelection()
      unselected: (e, ui) =>
        $(ui.unselected).parents('.result').removeClass('selected')
        @afterSelection()

  # Make an instance of our Search utility and setup endless scrolling.
  setupSearch: ->
    @scrollReady = false
    @search = new arcs.utils.Search 
      container: $('.search-wrapper')
      order: @options.sort
      run: false
      loader: true
      # This callback will be fired each time a search is done.
      success: =>
        @router.navigate "#{encodeURIComponent(@search.query)}/p#{@search.page}"
        # Setup the endless scroll unless it's already been done.
        @setupScroll() and @scrollReady = true unless @scrollReady
        @setupHelp()
        @render()

  # Setup the endless scroll. This is called after we've received our first set
  # of results. 
  setupScroll: ->
    [$actions, $results] = [@$('#search-actions'), @$('#search-results')]
    $window = $(window)
    pos = $actions.offset().top - 10

    $window.scroll =>
      # Toggle the toolbar's fixed position
      if $window.scrollTop() > pos
        $actions.addClass('toolbar-fixed').width $results.width() + 22
      else
        $actions.removeClass('toolbar-fixed').width 'auto'

    # Fix the toolbar width on resizes. 
    $window.resize ->
      $actions.width($results.width() + 23) if $window.scrollTop() > pos

  setupHelp: ->
    unless $('.search-help-btn').length
      $('.VS-search-inner').append(arcs.tmpl 'search/help-toggle')
      $('.search-help-btn').click(@showHelp)
      $('.search-help-close').click(@closeHelp)

  # Toggle between list and grid view.
  toggleView: ->
    @options.grid = !@options.grid
    @$('#grid-btn').toggleClass 'active'
    @$('#list-btn').toggleClass 'active'
    @render()

  # Scroll to the top of the page.
  scrollTop: ->
    # The animation time should be relative to our position on the page.
    time = ($(window).scrollTop() / $(document).height()) * 1000
    $('html, body').animate {scrollTop: 0}, time

  # Set the search sort (a.k.a. order). This triggers a new search
  # and subsequent render.
  setSort: (e) ->
    @options.sort = e.target.id.match(/sort-(\w+)-btn/)[1]
    @$('.sort-btn .icon-ok').remove()
    @$(e.target).append @make 'i', class: 'icon-ok'
    @$('#sort-btn span#sort-by').html @options.sort
    @search.run null,
      order: @options.sort
      direction: @options.sortDir

  # Set the search sort direction--either 'asc' or 'desc'.
  setSortDir: (e) ->
    @options.sortDir = e.target.id.match(/dir-(\w+)-btn/)[1]
    @$('.dir-btn .icon-ok').remove()
    @$(e.target).append @make 'i', class: 'icon-ok'
    @search.run null,
      order: @options.sort
      direction: @options.sortDir

  # Set the current search result page
  setPage: (e) ->
    e.preventDefault()
    $el = $(e.currentTarget)
    @search.options.page = $el.data('page')
    @search.run()

  unselectAll: (trigger=true) -> 
    @$('.result').removeClass('selected')
    arcs.bus.trigger 'selection' if trigger

  selectAll: (trigger=true) -> 
    @$('.result').addClass('selected')
    arcs.bus.trigger 'selection' if trigger
  
  # Select a result and unselect everything else, unless a modifier key
  # is pressed.n
  toggle: (e) ->
    # If <ctrl> <shift> or <meta> is pressed allow multi-select
    if not (e.ctrlKey or e.shiftKey or e.metaKey)
      @unselectAll false
    $(e.currentTarget).parents('.result').toggleClass('selected')
    arcs.bus.trigger 'selection'

  # Unselect all results unless a modifier key is held down, or
  # the target isn't right.
  maybeUnselectAll: (e) ->
    # If e is not an Event object, do it.
    return @unselectAll() unless e instanceof jQuery.Event 
    # If one of the modifier keys is held down, we won't do anything.
    return false if (e.metaKey or e.ctrlKey or e.shiftKey)
    # If the target is the image, we won't do anything.
    return false if $(e.target).attr 'src'
    @unselectAll()

  showHotkeys: ->
    return $('.hotkeys-modal').remove() if $('.hotkeys-modal').length
    new arcs.views.Hotkeys template: 'search/hotkeys'

  showHelp: ->
    $('.search-help').show()

  closeHelp: ->
    $('.search-help').hide()

  ### Render the search results ###
  
  # Syncs selection states between the ResultSet and the DOM elements that
  # represent them. Uses Underscore's defer to wait for the call stack to
  # clear.
  afterSelection: ->
    _.defer =>
      selected = $('.result.selected').map( -> $(@).data('id')).get()
      @search.results.unselectAll()
      @search.results.select selected if selected.length
      if @search.results.anySelected()
        $('.btn.needs-resource').removeClass 'disabled'
        # Blur the search input(s), so that hotkeys work as expected.
        $('#search input').blur()
      else
        $('.btn.needs-resource').addClass 'disabled'

  # Append more results. 
  #
  # We do this instead of a full render to stop the scrollbar from jumping in
  # certain browsers.
  append: ->
    return unless @search.results.length > @search.options.n
    results = new arcs.collections.ResultSet @search.getLast()
    @_render results: results.toJSON(), true

  # Render the results.
  render: ->
    @_render results: @search.results.toJSON()
    data = @search.results.query
    data.page = @search.page
    data.query = encodeURIComponent @search.query
    $('#search-pagination').html arcs.tmpl('search/paginate', results: data)

  # Actually render the results. Can append or replace.
  # If there are no results, adds a 'No Results' message.
  _render: (results, append=false) ->
    $results = $('#search-results')
    template = if @options.grid then 'search/grid' else 'search/list'
    $results[if append then 'append' else 'html'] arcs.tmpl(template, results)
    if not @search.results.length
      $results.html @make 'div', id:'no-results', 'No Results'
