# modal.coffee
# ------------
# The Modal view creates modal dialogs, given a set of options. 
# It handles binding button clicks and gathering input values.
# 
# options:
#   draggable:  make the modal draggable. 
#   dragHandle: selector to use as the draggable handle
#   backdrop:   use an overlay
#   keyboard:   <esc> will close the modal
#   show:       show the modal on init
#   class:      attach an extra class to the .modal div
#   title:      modal's header text
#   subtitle:   text displayed in first line of the modal body
#   inputs:     inputs to add to the modal. This is given as an object 
#               containing option objects. For example:
class arcs.views.Modal extends Backbone.View

  options:
    draggable: false
    dragHandle: null
    backdrop: true
    keyboard: true
    show: true
    class: ''
    title: 'No Title'
    subtitle: null
    template: 'ui/modal'
    inputs: {}
    buttons: {}

  initialize: ->
    # Secure a DOM element
    unless $('#modal').length
      $('body').append arcs.tmpl 'ui/modal_wrapper'
    @el = @$el = $('#modal')

    @$el.addClass @options.class

    # Render the template into the wrapper, make it use Underscore's template
    # function.
    @$el.html arcs.tmpl @options.template, @options

    # Do some extra setting up.
    _.each @options.inputs, (opts, k) -> 
      $sel = @$("#modal-#{k}-input")
      if opts.complete or opts.multicomplete
        arcs.utils.autocomplete 
          sel: $sel
          multiple: !!opts.multicomplete
          source: opts.multicomplete ? opts.complete

    # Draggable?
    @$el.draggable(handle: @options.dragHandle) if @options.draggable

    # Setup Bootstrap modal
    @$el.modal
      backdrop: @options.backdrop
      keyboard: @options.keyboard
      show: @options.show

    @_bindButtons()
  
  # Hide the dialog
  hide: -> 
    @$el.modal 'hide'
    @remove()
   
  # Show the dialog
  show: -> 
    @$el.modal 'show'

  # Gather the values from each input.
  _getValues: ->
    values = {}
    for key in _.keys(@options.inputs)
      values[key] = @$("#modal-#{key}-input").val()
    values

  # Bind to the 'click' event on each button.
  _bindButtons: ->
    # Iterate through the buttons and bind them.
    for key in _.keys(@options.buttons)
      # Use one to avoid double triggers:
      @$("button#modal-#{key}-button").one 'click', (e) =>
        key = e.target.id.match(/modal-(\w+)-button/)[1]
        button = @options.buttons[key]
        # Button value is a function: call it.
        if _.isFunction button
          button @_getValues()
        # Object?
        else
          [callback, context] = [button.callback, button.context ? window]
          # Bind the callback to an object and call it.
          _.bind(callback, context) @_getValues()
        # Hide the dialog, unless told not to.
        unless button.close? or button.close
          @hide()
