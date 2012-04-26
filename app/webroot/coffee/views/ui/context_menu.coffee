# context_menu.coffee
# -------------------
# Our little context menu. It's not quite as flexible as the 2000 loc 
# plugins, but gets the job done.
#
# To use it, provide an `options` object of menu items paired to callback
# functions. If a `context` is set, the callbacks will be called within it.
# The `onShow` option takes a callback that we'll call when the context menu
# is shown--it's provided the jQuery.Event object that triggered the show.
class arcs.views.ContextMenu extends Backbone.View
  events:
    'click *': 'hide'

  options:
    options:
      'Example option': ->
      'Another option': ->
    context: window
    onShow: ->

  initialize: ->
    $('.context-menu').remove()
    $('body').append arcs.tmpl 'ui/context_menu', options: @options.options
    @menu = $('.context-menu')
    @addEvents()

  show: (e) ->
    # Hide if already open.
    @hide()

    @menu.css
      position: 'absolute'
      top: e.pageY + 'px'
      left: e.pageX + 'px'
    @menu.show()

    @options.onShow e

    e.preventDefault()
    return false

  addEvents: ->
    @events["contextmenu #{@options.filter}"] = 'show'
    for opt, cb of @options.options
      continue unless @options.context[cb]?
      boundCb = _.bind @options.context[cb], @options.context
      id = arcs.inflector.identifierize opt
      @events["click #context-menu-option-#{id}"] = boundCb
    @delegateEvents()

  hide: (e) ->
    @menu.hide()
