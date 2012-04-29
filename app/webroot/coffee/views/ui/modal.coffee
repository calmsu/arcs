# modal.coffee
# ------------
# The Modal view creates a modal dialog. It uses the 'ui/modal' template 
# and renders the template according to the provided (or default options). 
#
# Buttons and inputs are defined as objects, rather than using markup.
# This means fewer templates, and allows us to automate binding to click
# events and gathering input values. Buttons accept a callback function.
#
# The options (when using the default template) are described below.
#
# options:     
#   draggable:  Make the modal draggable (header is the handle).
#   backdrop:   Use an overlay
#   keyboard:   <esc> will close the modal
#   show:       Show the modal on init
#   class:      Attach an extra class to the .modal div
#   title:      Modal's header text
#   subtitle:   Text displayed at the top of the modal body
#
#   template:   Use a special template. We'll provide the options object
#               to the template interpolator, so custom templates can use
#               their own custom options.
#
#   inputs:     Inputs to add to the modal. These are given as an object 
#               of options objects. For example:
#
#                 email:
#                   type: 'text'
#                   required: true
#                   value: 'john.doe@example.com'
#                   complete: arcs.complete.email
#
#               If you're happy with the defaults, you can also do:
#
#                 title: true
#
#               By default, we'll create a label element with the 
#               capitalized property string. If you don't want a label,
#               use `label: false`. If you want a different label, use
#               `label: 'Some other label'`.
#
#   buttons:    Buttons to add to the modal. As with inputs, these are
#               given as options objects. For example:
#
#                 save:
#                   callback: someCallback
#                   context: this
#                   validate: true
#                   class: 'btn btn-success'
#                   close: false
#
#               Or simply:
#
#                 cancel: ->
#
#               Callbacks are called with a values object that contains
#               the value of each input. The property names will be
#               the same as defined in `inputs`. We'll close the dialog
#               after the callback is fired, unless `close: false` is
#               given or `validate: true` was given and validation failed.
#                   
class arcs.views.Modal extends Backbone.View

  # defaults
  options:
    draggable: true
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
    # Delete any existing modal and make a new one. (Don't resuse.)
    $('#modal').remove()
    $('.modal-backdrop').remove()
    $('body').append arcs.tmpl 'ui/modal_wrapper'
    @el = @$el = $('#modal')

    # Add any classes to the modal el.
    @$el.addClass(@options.class) if @options.class

    # Render the template into the wrapper, make it use Underscore's template
    # function.
    @$el.html arcs.tmpl @options.template, @options

    # Do some extra setting up.
    for name, options of @options.inputs
      $sel = @$("#modal-#{name}-input")
      if options.complete or options.multicomplete
        arcs.utils.autocomplete 
          sel: $sel
          multiple: !!options.multicomplete
          source: options.multicomplete ? options.complete

    # Draggable?
    if @options.draggable
      @$el.draggable(handle: @$('.modal-header'))
      @$('.modal-header').css 'cursor', 'move'

    # Setup Bootstrap modal
    @$el.modal
      backdrop: @options.backdrop
      keyboard: @options.keyboard
      show: @options.show

    @bindButtons()
  
  # Hide the dialog
  hide: -> 
    @$el.modal 'hide'
   
  # Show the dialog
  show: -> 
    @$el.modal 'show'

  isOpen: ->
    @$el.is ':visible'

  # Returns true if each input validates (according to the configured
  # validation). Otherwise, it returns false and displays the error.
  validate: ->
    @$('#validation-error').hide()
    @$('.error').removeClass('error')
    values = @getValues()
    required = []
    for name, options of @options.inputs
      if options.required
        unless values[name].replace(/\s/g, '').length
          required.push name

    return true unless required.length

    for name in required
      @$("#modal-#{name}-input").addClass('error')
      @$("label[for='modal-#{name}']").addClass('error')
    @$('#validation-error').show().html 'Looks like you missed a required field.'
    false

  # Gather the values from each input.
  getValues: ->
    values = {}
    for name of @options.inputs
      values[name] = @$("#modal-#{name}-input").val()
    values

  # Bind to the 'click' event on each button.
  bindButtons: ->
    # Iterate through the buttons and bind them.
    for name of @options.buttons
      @$("button#modal-#{name}-button").click (e) =>
        name = e.target.id.match(/modal-([\w-]+)-button/)[1]
        options = @options.buttons[name]
        # Button options is actually a function.
        if _.isFunction options
          cb = options
        # Object?
        else
          [callback, context] = [options.callback ? (->), options.context ? window]
          # Bind the callback to an object and call it.
          cb = _.bind(callback, context)
        valid = if options.validate then @validate() else true
        cb(@getValues()) if valid
        # Hide the dialog, unless told not to.
        @hide() unless (options.close? and options.close) or !valid
