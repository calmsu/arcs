# keys.coffee
# -----------
# Hotkey binding
#
# The keys object provides an interface for registering key mappings with a 
# callback that will be fired anytime the key (combination) is pressed. 
#
# The keydown event is bound to the document object. Key presses within a text
# accepting field will be ignored.
arcs.utils.keys = 

    initialize: ->
        $(document).bind 'keydown', @delegate
    
    # Inspect the event and see if any matching hotkeys are registered. If so,
    # fire the relevant callback.
    #
    #   e - a W3C compliant event object.
    delegate: (e) =>

        # Is it a text field? We don't do those here. 
        # (borrowed from John Resig's jquery.hotkeys)
        if e.target.type == 'text' or /textarea|select/i.test e.target.nodeName
            # Let it bubble.
            return true

        # Is a modifier pressed? To keep it simple, we don't care which one.
        if e.ctrlKey or e.shiftKey or e.altKey or e.metaKey
            modifier = true
        else
            modifier = false

        # Get matching mappings
        mappings = arcs.utils.keys.get e.which, modifier
        if not mappings.length
            return true

        # Iterate the mappings.
        # It's unlikely that there'd be more than one, but it's allowed.
        # We'll execute all of them, and afterwards bubble up if any request it.
        bubble = false
        for m in mappings
            bubble = true if m.bubble
            # Bind it to a context, or use the current one.
            if m.context
                callback = _.bind m.callback, m.context
            else
                callback = m.callback
            callback(e)

        # Bubble up or block.
        unless bubble
            e.preventDefault()
            return false
        true

    # Register a mapping.
    #
    #   key      - single letter or special character.
    #   modifier - bool, one of [shift,ctl,meta,alt] must also be pressed
    #   callback - function to call when the key (combination) is pressed.
    #   context  - bind the callback to an object. It will default to the 
    #              DOM's document element (which the keydown is bound to).
    #   bubble   - bubble up the event. By default, we'll return false and
    #              block further action with preventDefault().
    add: (key, modifier, callback, context=null, bubble=false) ->
        @mappings.push
            key: key
            modifier: modifier
            callback: callback
            context: context
            bubble: bubble
        @mappings

    # Get a mapping.
    #
    #   keyCode  - value of e.which or e.keyCode
    #   modifier - matches must match this modifier setting.
    get: (keyCode, modifier=false) ->
        matches = _.filter @mappings, (map) =>
            map.key == @humanize(keyCode) and map.modifier == modifier

    # Collection of key mapping objects here.
    mappings: []

    # Convert key codes to human. Our list of special keys is incomplete, so
    # this may fail for certain codes.
    #
    #   keyCode - value of e.which or e.keyCode
    humanize: (keyCode) ->
        # Special key?
        if _.has @specialKeys, keyCode
            return @specialKeys[keyCode]
        # Nope, normal key.
        String.fromCharCode(keyCode).toLowerCase()

    # Partial list of special keys (just the ones we're likely to use).
    specialKeys: 
        8: "backspace"
        9: "tab"
        13: "return"
        16: "shift"
        17: "ctrl"
        18: "alt"
        27: "esc"
        32: "space"
        37: "left" 
        38: "up"
        39: "right"
        40: "down" 
        107: "+"
        109: "-"

# Fire it up. There's no harm if we don't have any mappings.
arcs.utils.keys.initialize()
