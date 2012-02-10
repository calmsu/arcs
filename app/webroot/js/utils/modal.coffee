# modal.coffee
# ------------
# Wraps up modal dialog logic.
#
# The goal here is to avoid dealing with things like opening/closing the modal
# and binding click events to buttons in each view. We'll do it all here and 
# provide options to customize the functionality.
arcs.utils.modal = (options) ->

    # Default options
    defaults =
        # Mustache template
        template: ''
        # Values to evaluate the template with
        templateValues: {}
        # Make the modal draggable?
        draggable: false
        handle: null
        # Give the modal a backdrop?
        backdrop: true
        # Provide an array of input ids and we'll retrieve their values and 
        # provide them to the callback.
        inputs: []
        # Provide an array of button objects. Each key should be the button's 
        # id. We'll provide any values garnered from the inputs option, along
        # with the modal dialog element. An example is given below:
        buttons: 
            save:
                callback: (vals, $modal) ->
                    # do something
                    $modal.modal 'hide'
                context: @
                closeAfter: true

    # Override defaults with givens.
    options = _.extend defaults, options 

    # Cancel button is free if not defined.
    # Can't put it in defaults because extend is not recursive.
    if not options.buttons.cancel?
        options.buttons.cancel =
            closeAfter: true

    # Check to see if the page already has a modal DOM el.
    if not $('#modal').length
        $('body').append(arcs.templates.modalWrapper)
    $modal = $('#modal')

    # Set the modal
    $modal.modal
        backdrop: options.backdrop

    # Evaluate and inject the template with any values. 
    $modal.html Mustache.render options.template, options.templateValues
    $modal.modal 'show'

    # If they want it draggable, make it so (with jQuery UI).
    $modal.draggable(handle: options.handle) if options.draggable

    # Iterate through the buttons and bind them. This is where it gets 
    # complicated.
    for id in _.keys(options.buttons)

        # Use one to avoid double triggers:
        $modal.find("##{id}").one 'click', (e) =>

            # Get the input vals, if any inputs were given.
            vals = {}
            if options.inputs.length
                for id_ in options.inputs
                    vals[id_] = $modal.find("##{id_}").val()

            # Since this is async, we can't rely on the loop iterator inside.
            button = options.buttons[e.target.id]
            if typeof button == 'function'  
                callback = button
                context = @
                closeAfter = true
            else 
                # Backup values for context & callback.
                context = button.context ? @
                callback = button.callback ? ->
                closeAfter = button.closeAfter ? true

            # Bind the function to a context, if given.
            # This allows callbacks to operate in the context of their own 
            # object. (For example, Search.tagSelected bound to Search)
            callback = _.bind(callback, context)

            # Fire the callback with the vals we retrieved and the modal.
            callback(vals, $modal)

            if closeAfter
                $modal.modal 'hide'

    # Return the modal el.
    $modal
