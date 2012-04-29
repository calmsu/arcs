# search.coffee
# -------------
# Search View. Select and perform bulk actions on search results.
arcs.views.search ?= {}
class arcs.views.search.Search extends Backbone.View

  options:
    sort: 'modified'
    grid: true
    url: arcs.baseURL + 'search/'
    numResults: 30

  ### Initialize and define events ###

  initialize: ->
    @setupSelect() and @setupSearch()

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
    @search.run() unless @router.searched

    # Set up some event bindings.
    @search.results.on 'change remove', @render, @
    arcs.bus.on 'selection', @afterSelection, @

    # <ctrl>-a to select all
    arcs.keys.map @,
      'ctrl+a': @selectAll
      t: @scrollTop

  events:
    'click img'              : 'toggle'
    'click .result'          : 'maybeUnselectAll'
    'click #search-results'  : 'maybeUnselectAll'
    'click #grid-btn'        : 'toggleView'
    'click #list-btn'        : 'toggleView'
    'click #top-btn'         : 'scrollTop'
    'click .sort-btn'        : 'setSort'

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
    @searchPage = 1
    @scrollReady = false
    @search = new arcs.utils.Search 
      container: $('.search-wrapper')
      order: @options.sort
      run: false
      loader: true
      # This callback will be fired each time a search is done.
      success: =>
        @router.navigate encodeURIComponent @search.query
        @searchPage = 1
        # Setup the endless scroll unless it's already been done.
        @setupScroll() and @scrollReady = true unless @scrollReady
        @render()

  # Setup the endless scroll. This is called after we've received our first set
  # of results. 
  setupScroll: ->
    $actions = @$('#search-actions')
    $results = @$('#search-results')
    $window = $(window)
    pos = $actions.offset().top - 10

    $window.scroll =>
      # Toggle the toolbar's fixed position
      if $window.scrollTop() > pos
        $actions.addClass('toolbar-fixed').width $results.width() + 22
        @$('#top-btn').show()
      else
        $actions.removeClass('toolbar-fixed').width 'auto'
        @$('#top-btn').hide()

      # If the scroll position is at the bottom, get the more results.
      if $window.scrollTop() == $(document).height() - $window.height()
        # When the modulus is non-zero, it means the last search returned
        # fewer results than allowed, and we don't need to search again.
        return unless @search.results.length % @options.numResults == 0
        @search.run null,
          add: true
          page: @searchPage += 1
          order: @options.sort
          success: => @append()

    # Fix the toolbar width on resizes. 
    $window.resize ->
      $actions.width($results.width() + 23) if $window.scrollTop() > pos

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
    id = e.currentTarget.id
    @options.sort = e.target.id.match(/sort-(\w+)-btn/)[1]
    @$('.sort-btn .icon-ok').remove()
    @$(e.currentTarget).append @make 'i', class: 'icon-ok'
    @$('#sort-btn span#sort-by').html @options.sort
    @search.run null,
      order: @options.sort

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
  # We do this instead of a full render to stop the scrollbar from 
  # jumping in certain browsers.
  append: ->
    return unless @search.results.length > @options.numResults
    # Get new results after the ones already displayed.
    rest = @search.results.rest @search.results.length - @options.numResults
    results = new arcs.collections.ResultSet rest
    @_render results: results.toJSON(), true

  # Render the results.
  render: ->
    @_render results: @search.results.toJSON()

  # Actually render the results. Can append or replace.
  # If there are no results, adds a 'No Results' message.
  _render: (results, append=false) ->
    $results = $('#search-results')
    template = if @options.grid then 'search/grid' else 'search/list'
    content = arcs.tmpl template, results
    if append
      $results.append content
    else
      $results.html content
    unless @search.results.length
      $results.html @make 'div', id:'no-results', 'No Results'
