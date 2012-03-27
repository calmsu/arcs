# search.coffee
# -------------
# Search View. Select and perform bulk actions on search results.
class arcs.views.Search extends Backbone.View

  RESULTS_PER_PAGE: 30 

  ### Initialize and define events ###

  initialize: ->
    @setupSelect() and @setupSearch() and @setupScroll()

    # Init our sub-view for actions.
    @actions = new arcs.views.SearchActions
      el: @$el
      collection: @search.results

    # Setup our Router
    @router = new arcs.routers.Search
      search: @search

    # Start Backbone.history
    Backbone.history.start
      pushState: true
      root: arcs.baseURL + 'search/'
    
    # Search unless the Router already delegated it.
    @search.run() unless @router.searched

    @search.results.on 'remove', @render, @

    # Grid view unless init-ed with false
    @grid ?= true

    # <ctrl>-a to select all
    arcs.keys.add 'a', true, @selectAll, @

  events:
    'click img'              : 'toggle'
    'click .result'          : 'maybeUnselectAll'
    'click #search-results'  : 'maybeUnselectAll'
    'click #grid-btn'        : 'toggleView'
    'click #list-btn'        : 'toggleView'
    'click #top-btn'         : 'scrollTop'

  ### More involved setups run by the initialize method ###

  # Set up drag-to-select, using jQuery.ui.selectable
  setupSelect: ->
    @$el.find('#search-results').selectable
      # Little bit of drag tolerance
      distance: 20
      # Images are the selectables
      filter: 'div.img-wrapper img'
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
    @search = new arcs.utils.Search 
      container: $('#search-wrapper')
      run: false
      loader: true
      # This callback will be fired each time a search is done.
      success: =>
        @router.navigate encodeURIComponent(@search.query)
        @searchPage = 1
        # Render the our results.
        @render()
    @searchPage = 1

  setupScroll: ->
    $actions = @$('#search-actions')
    $results = @$('#search-results')
    $window = $(window)
    pos = $actions.offset().top - 10

    $window.scroll =>
      # Toggle the toolbar's fixed position
      if $window.scrollTop() > pos
        $actions.addClass('toolbar-fixed').width $results.width() + 23
        @$('#top-btn').show()
      else
        $actions.removeClass('toolbar-fixed').width 'auto'
        @$('#top-btn').hide()

      # If the scroll position is at the bottom, get the more results.
      if $window.scrollTop() == $(document).height() - $window.height()
        # When the modulus is non-zero, it means the last search returned
        # fewer results than allowed, and we don't need to search again.
        return unless @search.results.length % @RESULTS_PER_PAGE == 0
        @searchPage += 1
        @search.run null,
          add: true
          page: @searchPage
          success: =>
            @append()

    # Fix the toolbar width on resizes. 
    # TODO: do this in the stylesheet.
    $window.resize ->
      $actions.width($results.width() + 23) if $window.scrollTop() > pos

  # Toggle between list and grid view.
  toggleView: ->
    @grid = !@grid
    @$('#grid-btn').toggleClass 'active'
    @$('#list-btn').toggleClass 'active'
    @render()

  # Scroll to the top of the page.
  scrollTop: ->
    # The animation time should be relative to our position on the page.
    time = ($(window).scrollTop() / $(document).height()) * 1000
    $('html, body').animate {scrollTop: 0}, time

  unselectAll: -> 
    @$('.result').removeClass('selected') and @afterSelection()

  selectAll: -> 
    @$('.result').addClass('selected') and @afterSelection()
  
  # Select a result and unselect everything else, unless a modifier key
  # is pressed.n
  toggle: (e) ->
    # If <ctrl> <shift> or <meta> is pressed allow multi-select
    if not (e.ctrlKey or e.shiftKey or e.metaKey)
      @unselectAll()
    $(e.currentTarget).parents('.result').toggleClass('selected')
    @afterSelection()

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
      selected = $('.result.selected').map ->
        $(@).attr('data-id')
      unselected = $('.result').not('.selected').map ->
        $(@).attr('data-id')
      @search.results.select selected.get()
      @search.results.unselect unselected.get()
      if @search.results.anySelected()
        $('.btn.needs-resource').removeClass 'disabled'
      else
        $('.btn.needs-resource').addClass 'disabled'

  # Append more results. 
  #
  # We do this instead of a full render to stop the scrollbar from 
  # jumping in certain browsers.
  append: ->
    return unless @search.results.length > @RESULTS_PER_PAGE
    # Get new results after the ones already displayed.
    rest = @search.results.rest @search.results.length - @RESULTS_PER_PAGE
    results = new arcs.collections.ResultSet rest
    @_render results: results.toJSON(), true

  # Render the results.
  render: ->
    @_render results: @search.results.toJSON()

  # Actually render the results. Can append or replace.
  # If there are no results, adds a 'No Results' message.
  _render: (results, append=false) ->
    $results = $('#search-results')
    template = if @grid then 'search/grid' else 'search/list'
    content = arcs.tmpl template, results
    if append
      $results.append content
    else
      $results.html content
    unless @search.results.length
      $results.html @make 'div', id:'no-results', 'No Results'
