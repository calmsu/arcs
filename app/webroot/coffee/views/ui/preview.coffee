# preview.coffee
# --------------
# Little view for previewing a collection of Resource models.
class arcs.views.Preview extends Backbone.View

  options:
    # Start index.
    index: 0
    # Template to use. This should be a string understandable by `arcs.tmpl`.
    template: 'search/preview'

  # Fetch a modal to hold our preview, register our hotkeys, and delegate the
  # initial set and render.
  initialize: ->
    unless $('#modal').length
      $('body').append arcs.tmpl 'ui/modal_wrapper'
    @el = @$el = $('#modal')
    @$el.modal()
    arcs.keys.map @,
      left: @prev
      right: @next
    @set @options.index, true

  # Set up the previous and next buttons.
  events:
    'click #prev-btn': 'prev'
    'click #next-btn': 'next'

  # Set the incremented index. `set` will worry about whether or not this exists.
  prev: -> 
    @set @index - 1

  # Set the decremented index. `set` will worry about whether or not this exists.
  next: -> 
    @set @index + 1

  # Set the index and `render`.
  set: (index, force=false) ->
    # Don't set the index unless it's valid.
    return unless 0 <= index < @collection.length or force
    # Grab the model at the index.
    @model = @collection.at index
    @index = index
    # Start preloading the next and render.
    @_preloadNext()
    @render()

  # Preload the next resource. This delegates to `arcs.preload`
  _preloadNext: ->
    if @index + 1 < @collection.length
      arcs.preload @collection.at(@index + 1).get 'url'

  # Hide the modal, remove it from the DOM, and kill the events.
  remove: ->
    @$el.modal 'hide'
    super()
    @undelegateEvents()
    
  # Render the preview using the given or default template.
  render: ->
    # Provide the page info to the template.
    pageInfo =
      page: @index + 1
      count: @collection.length
    # Extend the page info with the Resource model and interpolate.
    @$el.html arcs.tmpl @options.template, 
      _.extend @model.toJSON(), pageInfo
    @
