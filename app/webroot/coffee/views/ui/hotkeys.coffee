# hotkeys.coffee
# --------------
class arcs.views.Hotkeys extends Backbone.View

  initialize: ->
    ctrl = if navigator.appVersion.indexOf('Mac') != -1 then '⌘' else 'ctrl'
    unless $('.hotkeys-modal').length
      $('body').append arcs.tmpl(@options.template, {ctrl: ctrl})
    @el = @$el = $('.hotkeys-modal')
    $('.hotkeys-modal').modal backdrop: false
    @delegateEvents()

  events: 
    'click .hotkeys-close': 'close'

  close: ->
    @el.remove()
