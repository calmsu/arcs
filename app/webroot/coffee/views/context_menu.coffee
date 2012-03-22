class arcs.views.ContextMenu extends Backbone.View

  events:
    'click *'     : 'hide'

  options:
    options:
      'Example option': ->
      'Another option': ->
    context: window

  initialize: ->
    $('.context-menu').remove()
    $('body').append arcs.tmpl 'ui/context_menu', options: @options.options
    @menu = $('.context-menu')
    @addEvents()

  show: (e) ->
    $(e.currentTarget).click()
    @menu.css
      position: 'absolute'
      top: e.pageY + 'px'
      left: e.pageX + 'px'
    @menu.show()

    e.preventDefault()
    return false

  addEvents: ->
    @events["contextmenu #{@options.filter}"] = 'show'
    for opt, cb of @options.options
      continue unless @options.context[cb]?
      boundCb = _.bind @options.context[cb], @options.context
      @events["click #context-menu-option-#{opt.replace(/\s/g, '-')}"] = boundCb
    @delegateEvents()

  hide: (e) ->
    @menu.hide()
