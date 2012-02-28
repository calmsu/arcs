# modal.coffee
# ------------
# Wraps up modal dialog logic.
#
# The Modal class establishes some conventions for modal dialogs within ARCS.
# The actual modal functionality is delegated to Bootstrap's modal plugin,
# this class handles binding buttons to callbacks and gathering input values.
class arcs.utils.Modal

    # Construct a new Modal instance.
    #
    # options:
    #   template       - Mustache template 
    #   templateValues - values to evaluate the template with.
    #   draggable      - make the modal draggable.
    #   handle         - DOM element to use as drag handle.
    #   backdrop       - use a backdrop when displaying the modal.
    #   class          - add a class to the modal. To add more than one, 
    #                    just provide them in a single string, 
    #                    whitespace-separated.
    #   inputs         - array of input ids. We'll retrieve the value 
    #                    of each matched input and provide them as an 
    #                    object to the button callbacks, and via @values()
    #   buttons        - object with keys that map to button (or anchor) ids
    #                    and values that are either a callback or an object of:
    #
    #                        callback =
    #                           callback: fn
    #                           context: object
    #                           keepOpen: bool
    #
    #                    We'll bind to the button's click event and fire the
    #                    given callback with any input values as an argument.
    #
    #                    The modal will close automatically after a button is
    #                    clicked, unless keepOpen is truthy.
    constructor: (options) ->

        # Default options
        defaults =
            template: ''
            templateValues: {}
            draggable: false
            handle: null
            backdrop: true
            class: null
            inputs: []
            buttons: {}

        # Override defaults with givens.
        @options = _.extend defaults, options 

        # Add a modal div to the DOM if not there.
        unless $('#modal').length
            $('body').append(arcs.templates.modalWrapper)
        @el = $('#modal')

        # Add the class 
        @el.addClass @options.class if @options.class?

        # If they want it draggable, make it so (with jQuery UI).
        @el.draggable(handle: @options.handle) if @options.draggable

        # Set the modal
        @el.modal
            backdrop: @options.backdrop
            keyboard: true
            show: false

        if @el.attr('data-first') != 'false'
            @el.attr 'data-first', 'true'
        @show()

        @_bindButtons()

    hide: ->
        @el.modal 'hide'

    show: ->
        # Evaluate and inject the template with any values. 
        @el.html Mustache.render @options.template, @options.templateValues
        @el.modal 'show'
        if @el.attr('data-first') == 'true'
            @el.css('right', '-400px').animate(right: '0px')
            @el.attr 'data-first', 'false'

    visible: ->
        @el.is ':visible'

    values: ->
        # Get the input vals, if any inputs were given.
        vals = {}
        if @options.inputs.length
            for id in @options.inputs
                vals[id] = @el.find("##{id}").val()
        vals

    # Bind to each button or 
    _bindButtons: ->
        # Iterate through the buttons and bind them. 
        for id in _.keys(@options.buttons)
            # Use one to avoid double triggers:
            @el.find("a##{id}, button##{id}").one 'click', (e) =>
                button = @options.buttons[e.target.id]
                # Button value is a function: call it.
                if _.isFunction button
                    button @values()
                # Object?
                else
                    [callback, context] = [button.callback, button.context ? null]
                    # Bind the callback to an object, if given.
                    callback = _.bind(callback, context) if context?
                    callback @values()
                # Close the modal, unless told not to.
                unless button.keepOpen
                    @hide()
