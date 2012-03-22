class arcs.views.Preview extends Backbone.View

  options:
    index: 0

  initialize: ->
    unless $('#modal').length
      $('body').append arcs.tmpl 'ui/modal_wrapper'
    @el = @$el = $('#modal')
    @$el.modal()
    arcs.keys.add 'left', false, @prev, @
    arcs.keys.add 'right', false, @next, @
    @set @options.index

  events:
    'click #prev-btn': 'prev'
    'click #next-btn': 'next'

  prev: -> 
    @set @index - 1

  next: -> 
    @set @index + 1

  set: (index) ->
    index = 0 if index < 0
    index = @collection.length - 1 if index >= @collection.length
    @model = @collection.at index
    @index = index
    @_preloadNext()
    @render()

  _preloadNext: ->
    if @index + 1 < @collection.length
      arcs.preload @collection.at(@index + 1).get 'url'
    
  render: ->
    pageInfo =
      page: @index + 1
      count: @collection.length
    @$el.html arcs.tmpl 'search/preview', 
      _.extend pageInfo, @model.toJSON()
    @
